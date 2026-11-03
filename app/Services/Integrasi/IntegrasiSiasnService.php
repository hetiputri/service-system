<?php

namespace App\Services\Integrasi;

use App\Models\SiasnDataUtamaModel;
use App\Models\SiasnSyncLogModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

/**
 * Perbandingan data ASN SIMPEG (mysql_second) dengan data SIASN hasil tarikan API (siasn_data_utama, mysql_third).
 * Kedua database terpisah, jadi pencocokan per NIP dilakukan di PHP, bukan join SQL.
 * Atribut yang punya ID BKN di referensi SIMPEG dibandingkan lewat ID, sisanya lewat teks.
 */
class IntegrasiSiasnService
{
    // Kelompok data yang dibandingkan per pegawai (key => label)
    public const ATRIBUT_PEGAWAI = [
        'Data Pribadi' => [
            'nama' => 'Nama Lengkap',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
        ],
        'Data Kepegawaian' => [
            'id_bkn' => 'ID BKN (PNS ID)',
            'status_kepegawaian' => 'Status Kepegawaian',
            'tmt_cpns' => 'TMT CPNS',
            'tmt_pns' => 'TMT PNS',
            'golongan' => 'Golongan/Ruang',
            'tmt_golongan' => 'TMT Golongan',
        ],
        'Jabatan & Unit Kerja' => [
            'jenis_jabatan' => 'Jenis Jabatan',
            'jabatan' => 'Jabatan',
            'unit_kerja' => 'Unit Kerja',
        ],
        'Pendidikan' => [
            'pendidikan' => 'Pendidikan Terakhir',
        ],
    ];

    // data yang boleh di-sync dari SIASN ke SIMPEG => kolom tbl_pegawai (tahap pertama: data pribadi)
    public const KOLOM_SYNC_SIMPEG = [
        'nama' => 'nama_pegawai',
        'tempat_lahir' => 'tempat_lahir',
        'tanggal_lahir' => 'tanggal_lahir',
        'jenis_kelamin' => 'id_jenis_kelamin',
        'agama' => 'id_agama',
    ];

    // sama dengan kriteria pegawai aktif di PegawaiRepoImplement
    public const KEDUDUKAN_AKTIF = [1, 2, 3, 4, 5, 6];

    // KEDUDUKAN HUKUM ID di SIASN => status: 71 PPPK Aktif, 101 PPPK Paruh Waktu Aktif
    private const STATUS_PER_KEDUDUKAN_BKN = ['71' => 'PPPK', '101' => 'PPPK PW'];

    private const LABEL_JENIS_KELAMIN = ['M' => 'Laki-laki', 'F' => 'Perempuan'];

    private const TANPA_OPD = 'Unit kerja SIASN belum terpetakan di SIMPEG';

    // hasil ringkasan() disimpan di cache file (bisa beberapa MB), dihapus setelah import SIASN selesai
    private const KUNCI_CACHE = 'integrasi_siasn_ringkasan';
    private const DURASI_CACHE = 300;

    private ?array $referensi = null;

    private ?bool $siasnTersedia = null;

    /**
     * Ekspresi SQL NIP tanpa karakter selain angka. tbl_pegawai.nip ada yang berisi spasi (juga tab/non-breaking space
     * dari copy-paste), jadi pencocokan NIP di SQL memakai ini, sama dengan nip() di PHP. Butuh MySQL 8 (REGEXP_REPLACE).
     */
    public static function sqlNipAngka(string $kolom): string
    {
        return "REGEXP_REPLACE({$kolom}, '[^0-9]', '')";
    }

    public static function semuaKeyAtribut(): array
    {
        return array_keys(array_merge(...array_values(self::ATRIBUT_PEGAWAI)));
    }

    // cache dipakai selama data SIASN belum berubah, termasuk perubahan langsung di database (bukan lewat import)
    public function ringkasanTersimpan(): array
    {
        $versi = $this->versiDataSiasn();
        $tersimpan = Cache::store('file')->get(self::KUNCI_CACHE);
        if (is_array($tersimpan) && ($tersimpan['versi'] ?? null) === $versi) {
            return $tersimpan['data'];
        }

        $data = $this->ringkasan();
        Cache::store('file')->put(self::KUNCI_CACHE, ['versi' => $versi, 'data' => $data], self::DURASI_CACHE);

        return $data;
    }

    // penanda isi siasn_data_utama: berubah bila ada baris yang ditambah, diubah atau dihapus
    private function versiDataSiasn(): string
    {
        if (!$this->siasnTersedia()) {
            return 'tidak-tersedia';
        }

        $penanda = SiasnDataUtamaModel::query()->toBase()->selectRaw('COUNT(*) as jumlah, MAX(updated_at) as terakhir')->first();

        return $penanda->jumlah . '|' . $penanda->terakhir;
    }

    public static function hapusCache(): void
    {
        Cache::store('file')->forget(self::KUNCI_CACHE);
    }

    /**
     * Satu halaman daftar pegawai yang berbeda untuk tab di dashboard.
     * $jenis: hanyaDiSiasn, hanyaDiSimpeg atau atributBerbeda (key dari ringkasan()).
     */
    public function daftarPegawai(string $jenis, string $cari, int $mulai, int $jumlah, ?string $urutKolom = null, string $arah = 'asc'): array
    {
        $semua = $this->ringkasanTersimpan()[$jenis] ?? [];
        $data = $semua;

        $cari = mb_strtolower(trim($cari));
        if ($cari !== '') {
            $data = array_values(array_filter($data, fn($item) =>
                str_contains($item['nip'], $cari)
                || str_contains(mb_strtolower((string) $item['nama']), $cari)
                || str_contains(mb_strtolower(implode(' ', $item['atribut'] ?? [])), $cari)));
        }

        if ($urutKolom) {
            $nilai = fn($item) => $urutKolom === 'atribut' ? count($item['atribut'] ?? []) : mb_strtolower((string) ($item[$urutKolom] ?? ''));
            usort($data, fn($a, $b) => $arah === 'desc' ? $nilai($b) <=> $nilai($a) : $nilai($a) <=> $nilai($b));
        }

        return [
            'total' => count($semua),
            'terfilter' => count($data),
            'data' => array_slice($data, $mulai, $jumlah),
        ];
    }

    /**
     * Ringkasan untuk dashboard: total, rekap per kategori dan daftar pegawai yang berbeda.
     */
    public function ringkasan(): array
    {
        $siasn = $this->dataSiasn() ?? [];

        $hasil = [
            'siasnTersedia' => $this->siasnTersedia(),
            // tanpa data SIASN, perbandingan per pegawai tidak dijalankan (semua pegawai akan terhitung "hanya di SIMPEG")
            'adaDataSiasn' => count($siasn) > 0,
            'tanggalTarik' => $this->siasnTersedia() ? $this->tanggalTarikTerakhir() : null,
            'rekap' => ['status' => [], 'ruang' => [], 'jenis_kelamin' => [], 'opd' => []],
            'hanyaDiSimpeg' => [],
            'hanyaDiSiasn' => [],
            'atributBerbeda' => [],
        ];

        // semua ruang ditampilkan walaupun jumlahnya nol, urut sesuai ref_pangkat
        foreach ($this->referensi()['ruang'] as $ruang) {
            $hasil['rekap']['ruang'][$ruang] = ['simpeg' => 0, 'siasn' => 0];
        }

        // cadangan pencocokan lewat ID BKN (tbl_pegawai.id_pegawai_bkn = PNS ID) untuk data SIASN yang NIP-nya
        // tidak dimiliki pegawai aktif SIMPEG mana pun, mis. NIP berubah
        $nipSimpeg = $hasil['adaDataSiasn'] ? $this->nipSimpegAktif() : [];
        $nipSiasnPerIdBkn = [];
        foreach ($siasn as $nipSiasn => $dataSiasn) {
            $nipSiasnPerIdBkn[strtoupper((string) $dataSiasn['nilai']['id_bkn'])] = $nipSiasn;
        }

        foreach ($this->querySimpeg()->cursor() as $baris) {
            $nip = $this->nip($baris->nip);
            if ($nip === '') {
                continue;
            }

            $dataSimpeg = $this->petakanSimpeg($baris);
            $this->tambahRekap($hasil['rekap'], 'simpeg', $dataSimpeg);

            if (!$hasil['adaDataSiasn']) {
                continue;
            }

            $nipSiasn = isset($siasn[$nip]) ? $nip : null;
            $idBkn = strtoupper(trim((string) $baris->id_pegawai_bkn));
            if ($nipSiasn === null && $idBkn !== '') {
                $kandidat = $nipSiasnPerIdBkn[$idBkn] ?? null;
                if ($kandidat !== null && isset($siasn[$kandidat]) && !isset($nipSimpeg[$kandidat])) {
                    $nipSiasn = $kandidat;
                }
            }

            if ($nipSiasn === null) {
                $hasil['hanyaDiSimpeg'][] = $this->ringkasPegawai($nip, $dataSimpeg);
                continue;
            }

            $dataSiasn = $siasn[$nipSiasn];
            unset($siasn[$nipSiasn]);
            $this->tambahRekap($hasil['rekap'], 'siasn', $dataSiasn);

            $berbeda = $this->bandingkan($dataSimpeg, $dataSiasn);
            if ($berbeda) {
                $hasil['atributBerbeda'][] = $this->ringkasPegawai($nip, $dataSimpeg) + ['atribut' => array_column($berbeda, 'label')];
            }
        }

        // sisa data SIASN = tidak ada pasangannya di SIMPEG
        foreach ($siasn as $nip => $dataSiasn) {
            $this->tambahRekap($hasil['rekap'], 'siasn', $dataSiasn);
            $hasil['hanyaDiSiasn'][] = $this->ringkasPegawai((string) $nip, $dataSiasn);
        }

        return $hasil;
    }

    /**
     * Data satu pegawai dari kedua sistem, untuk halaman detail.
     */
    public function pegawai(string $nip): ?array
    {
        $barisSimpeg = $this->querySimpeg()->whereRaw(self::sqlNipAngka('p.nip') . ' = ?', [$nip])->first();

        $barisSiasn = null;
        if ($this->siasnTersedia()) {
            $barisSiasn = $this->querySiasn()->where('nipBaru', $nip)->first();
        }

        // cadangan lewat ID BKN bila salah satu sisi tidak ketemu lewat NIP
        if (!$barisSiasn && $barisSimpeg && trim((string) $barisSimpeg->id_pegawai_bkn) !== '' && $this->siasnTersedia()) {
            $barisSiasn = $this->querySiasn()->where('id', trim($barisSimpeg->id_pegawai_bkn))->first();
        }
        if (!$barisSimpeg && $barisSiasn) {
            $barisSimpeg = $this->querySimpeg()->where('p.id_pegawai_bkn', $barisSiasn->id)->first();
        }

        if (!$barisSimpeg && !$barisSiasn) {
            return null;
        }

        return [
            'simpeg' => $barisSimpeg ? $this->petakanSimpeg($barisSimpeg) : null,
            'siasn' => $barisSiasn ? $this->petakanSiasn($barisSiasn) : null,
        ];
    }

    /**
     * Daftar atribut yang tidak sama. Pegawai yang hanya ada di salah satu sistem dianggap berbeda di semua atribut.
     */
    public function bandingkan(?array $simpeg, ?array $siasn): array
    {
        $berbeda = [];
        foreach (self::ATRIBUT_PEGAWAI as $atribut) {
            foreach ($atribut as $key => $label) {
                if (!$simpeg || !$siasn || !$this->sama($simpeg, $siasn, $key)) {
                    $berbeda[] = ['key' => $key, 'label' => $label];
                }
            }
        }

        return $berbeda;
    }

    /**
     * Simpan data SIASN ke SIMPEG (tbl_pegawai) untuk atribut yang dipilih. Hanya data pribadi yang diizinkan,
     * ID referensi BKN dipetakan ke ID SIMPEG. Perubahan dan riwayatnya (siasn_sync_log) disimpan dalam satu transaksi:
     * bila pencatatan riwayat gagal, perubahan di SIMPEG dibatalkan. Mengembalikan jumlah kolom yang berubah.
     *
     * @throws RuntimeException pesan untuk pengguna bila data tidak bisa di-sync
     */
    public function syncKeSimpeg(string $nip, array $atribut, ?object $user): int
    {
        $pegawai = $this->pegawai($nip);
        if (!$pegawai || !$pegawai['simpeg']) {
            throw new RuntimeException('Pegawai belum ada di SIMPEG, data tidak bisa di-sync. Tambahkan pegawai melalui proses SIMPEG terlebih dahulu.');
        }
        if (!$pegawai['siasn']) {
            throw new RuntimeException('Data SIASN pegawai ini tidak ada.');
        }

        $label = array_merge(...array_values(self::ATRIBUT_PEGAWAI));
        $tidakBoleh = array_diff($atribut, array_keys(self::KOLOM_SYNC_SIMPEG));
        if ($tidakBoleh) {
            throw new RuntimeException('Data berikut belum bisa di-sync ke SIMPEG: ' . implode(', ', array_map(fn($k) => $label[$k] ?? $k, $tidakBoleh)) . '.');
        }

        $baru = [];
        $masalah = [];
        foreach ($atribut as $key) {
            try {
                $baru[self::KOLOM_SYNC_SIMPEG[$key]] = $this->nilaiUntukSimpeg($key, $pegawai['siasn']);
            } catch (RuntimeException $e) {
                $masalah[] = $label[$key] . ': ' . $e->getMessage();
            }
        }
        if ($masalah) {
            throw new RuntimeException('Tidak ada data yang disimpan. ' . implode(' ', $masalah));
        }

        $db = DB::connection('mysql_second');
        $idPegawai = $pegawai['simpeg']['id_pegawai'];
        $lama = (array) $db->table('tbl_pegawai')->where('id_pegawai', $idPegawai)->first(array_keys($baru));

        // kolom yang nilainya sudah sama tidak perlu ditulis ulang
        $atributPerKolom = array_flip(self::KOLOM_SYNC_SIMPEG);
        $berubah = array_filter($baru, fn($nilai, $kolom) => (string) ($lama[$kolom] ?? '') !== (string) $nilai, ARRAY_FILTER_USE_BOTH);
        if (!$berubah) {
            return 0;
        }

        $db->transaction(function () use ($db, $idPegawai, $berubah, $lama, $nip, $pegawai, $user, $atributPerKolom) {
            $db->table('tbl_pegawai')->where('id_pegawai', $idPegawai)->update($berubah + ['updated_at' => now()]);

            foreach ($berubah as $kolom => $nilai) {
                SiasnSyncLogModel::create([
                    'nip' => $nip,
                    'id_pegawai' => $idPegawai,
                    'id_siasn' => $pegawai['siasn']['nilai']['id_bkn'] ?? null,
                    'arah' => 'simpeg',
                    'atribut' => $atributPerKolom[$kolom],
                    'tabel' => 'tbl_pegawai',
                    'kolom' => $kolom,
                    'nilai_lama' => $lama[$kolom] ?? null,
                    'nilai_baru' => $nilai,
                    'id_user' => $user?->getKey(),
                    'nama_user' => $user->nama ?? null,
                ]);
            }
        });

        self::hapusCache();

        return count($berubah);
    }

    // nilai SIASN dalam bentuk kolom tbl_pegawai (ID referensi BKN -> ID SIMPEG)
    private function nilaiUntukSimpeg(string $key, array $siasn)
    {
        $teks = trim((string) ($siasn['nilai'][$key] ?? ''));
        $db = DB::connection('mysql_second');

        switch ($key) {
            case 'nama':
            case 'tempat_lahir':
                if ($teks === '') {
                    throw new RuntimeException('nilai SIASN kosong.');
                }
                if (mb_strlen($teks) > 100) {
                    throw new RuntimeException('nilai SIASN lebih dari 100 karakter (batas kolom SIMPEG).');
                }

                return $teks;

            case 'tanggal_lahir':
                $tanggal = SiasnDataUtamaModel::tanggalDariApi($teks);
                if (!$tanggal) {
                    throw new RuntimeException('tanggal SIASN kosong atau tidak valid.');
                }

                return $tanggal;

            case 'jenis_kelamin':
                $ids = $db->table('ref_jenis_kelamin')
                    ->where('jenis_kelamin_bkn', strtoupper((string) ($siasn['id']['jenis_kelamin'] ?? '')))
                    ->pluck('id_jenis_kelamin');

                return $this->satuReferensi($ids, 'ref_jenis_kelamin.jenis_kelamin_bkn');

            case 'agama':
                $kode = $this->kode($siasn['id']['agama'] ?? '');
                $ids = $kode === '' ? collect() : $db->table('ref_agama')->where('id_agama_bkn', (int) $kode)->pluck('id_agama');

                return $this->satuReferensi($ids, 'ref_agama.id_agama_bkn');
        }

        throw new RuntimeException('belum bisa di-sync.');
    }

    private function satuReferensi($ids, string $kolomPemetaan)
    {
        if ($ids->count() !== 1) {
            throw new RuntimeException($ids->isEmpty()
                ? "kode SIASN tidak ditemukan di {$kolomPemetaan}."
                : "kode SIASN cocok ke {$ids->count()} data di {$kolomPemetaan}, tidak bisa dipastikan.");
        }

        return $ids->first();
    }

    // tabel siasn_data_utama bisa diakses (koneksi mysql_third sudah diatur dan sudah di-migrate)
    public function siasnTersedia(): bool
    {
        if ($this->siasnTersedia !== null) {
            return $this->siasnTersedia;
        }

        try {
            return $this->siasnTersedia = DB::connection('mysql_third')->getSchemaBuilder()->hasTable('siasn_data_utama');
        } catch (Throwable $e) {
            report($e);
            return $this->siasnTersedia = false;
        }
    }

    // ---------------------------------------------------------------- SIMPEG

    private function querySimpeg()
    {
        $db = DB::connection('mysql_second');

        $cpns = $db->table('tblcpns')->selectRaw('id_pegawai, MAX(TMTCPNS) as tmt')->groupBy('id_pegawai');
        $pns = $db->table('tbl_pns')->selectRaw('id_pegawai, MAX(TMTPNS) as tmt')->groupBy('id_pegawai');
        $pangkat = $db->table('tbl_riwayat_pangkat')->selectRaw('id_pegawai, id_pangkat, MAX(tmt) as tmt')
            ->where('status_supervisi', 3)->groupBy('id_pegawai', 'id_pangkat');

        return $db->table('tbl_pegawai as p')
            ->leftJoin('ref_status_kepegawaian as st', 'p.id_status_kepegawaian', '=', 'st.id_status_kepegawaian')
            ->leftJoin('ref_jenis_kelamin as jk', 'p.id_jenis_kelamin', '=', 'jk.id_jenis_kelamin')
            ->leftJoin('ref_agama as ag', 'p.id_agama', '=', 'ag.id_agama')
            ->leftJoin('ref_pangkat as pk', 'p.id_pangkat', '=', 'pk.id_pangkat')
            ->leftJoin('ref_tipe_jabatan as tj', 'p.id_tipe_jabatan', '=', 'tj.id_tipe_jabatan')
            ->leftJoin('ref_jabatan as jb', 'p.id_jabatan', '=', 'jb.id_jabatan')
            ->leftJoin('ref_skpd as sk', 'p.id_satuan_organisasi', '=', 'sk.id_skpd')
            ->leftJoin('ref_skpd as induk', DB::raw('LEFT(sk.kode_skpd, 3)'), '=', 'induk.kode_skpd')
            ->leftJoinSub($cpns, 'cpns', 'cpns.id_pegawai', '=', 'p.id_pegawai')
            ->leftJoinSub($pns, 'pns', 'pns.id_pegawai', '=', 'p.id_pegawai')
            ->leftJoinSub($pangkat, 'rp', function ($join) {
                $join->on('rp.id_pegawai', '=', 'p.id_pegawai')->on('rp.id_pangkat', '=', 'p.id_pangkat');
            })
            // pendidikan terakhir pegawai, id_pend_bkn setara pendidikanTerakhirId di SIASN
            ->leftJoin('ref_tk_pendidikan as pd', 'p.id_pendidikan', '=', 'pd.id_tk_pendidikan')
            ->where('p.id_satuan_organisasi', '>', 0)
            ->whereIn('p.id_kedudukan_kepegawaian', self::KEDUDUKAN_AKTIF)
            ->orderBy('p.id_pegawai')
            ->select([
                'p.id_pegawai', 'p.nip', 'p.id_pegawai_bkn', 'p.nama_pegawai', 'p.tempat_lahir', 'p.tanggal_lahir',
                'jk.jenis_kelamin', 'jk.jenis_kelamin_bkn',
                'ag.agama', 'ag.id_agama_bkn',
                'st.status_kepegawaian',
                'cpns.tmt as tmt_cpns', 'pns.tmt as tmt_pns',
                'pk.gol_ruang', 'pk.id_pangkat_bkn', 'rp.tmt as tmt_golongan',
                'tj.tipe_jabatan', 'tj.id_bkn as id_tipe_jabatan_bkn',
                'jb.jabatan', 'jb.id_bkn as id_jabatan_bkn',
                'sk.skpd', 'sk.id_bkn as id_skpd_bkn', 'induk.skpd as opd',
                'pd.namaTkPendidikan as pendidikan', 'pd.id_pend_bkn',
            ]);
    }

    private function petakanSimpeg(object $b): array
    {
        return [
            'id_pegawai' => $b->id_pegawai,
            'nama' => $b->nama_pegawai,
            'unit_kerja' => $b->skpd,
            'status' => $this->teks($b->status_kepegawaian),
            'jk' => $b->jenis_kelamin_bkn,
            'ruang' => $b->gol_ruang,
            'opd' => $b->opd ?: $b->skpd,
            'nilai' => [
                'id_bkn' => $b->id_pegawai_bkn,
                'nama' => $b->nama_pegawai,
                'tempat_lahir' => $b->tempat_lahir,
                'tanggal_lahir' => $this->tanggal($b->tanggal_lahir),
                'jenis_kelamin' => $b->jenis_kelamin,
                'agama' => $b->agama,
                'status_kepegawaian' => $b->status_kepegawaian,
                'tmt_cpns' => $this->tanggal($b->tmt_cpns),
                'tmt_pns' => $this->tanggal($b->tmt_pns),
                'golongan' => $b->gol_ruang,
                'tmt_golongan' => $this->tanggal($b->tmt_golongan),
                'jenis_jabatan' => $b->tipe_jabatan,
                'jabatan' => $b->jabatan,
                'unit_kerja' => $b->skpd,
                'pendidikan' => $b->pendidikan,
            ],
            'id' => [
                'jenis_kelamin' => $b->jenis_kelamin_bkn,
                'agama' => $b->id_agama_bkn,
                'golongan' => $b->id_pangkat_bkn,
                'jenis_jabatan' => $b->id_tipe_jabatan_bkn,
                'jabatan' => $b->id_jabatan_bkn,
                'unit_kerja' => $b->id_skpd_bkn,
                'pendidikan' => $b->id_pend_bkn,
            ],
        ];
    }

    // ---------------------------------------------------------------- SIASN

    private function querySiasn()
    {
        return SiasnDataUtamaModel::query()->toBase()->select([
            'id', 'nipBaru', 'nama', 'tempatLahir', 'tglLahir', 'jenisKelamin', 'agama', 'agamaId',
            'statusPegawai', 'jenisPegawaiNama', 'kedudukanPnsId', 'kedudukanPnsNama', 'tmtCpns', 'tmtPns',
            'golRuangAkhir', 'golRuangAkhirId', 'tmtGolAkhir', 'jenisJabatan', 'jenisJabatanId',
            'jabatanNama', 'jabatanStrukturalId', 'jabatanFungsionalId', 'jabatanFungsionalUmumId',
            'unorId', 'unorNama', 'pendidikanTerakhirNama', 'pendidikanTerakhirId',
        ]);
    }

    /**
     * Data SIASN aktif per NIP, atau null bila tabel belum tersedia.
     * Aktif = kedudukan setara kedudukan aktif SIMPEG, atau kedudukan bernama "Aktif" (termasuk PPPK).
     */
    private function dataSiasn(): ?array
    {
        if (!$this->siasnTersedia()) {
            return null;
        }

        $kedudukanAktif = $this->referensi()['kedudukan_aktif_bkn'];
        $data = [];
        foreach ($this->querySiasn()->cursor() as $baris) {
            $nip = $this->nip($baris->nipBaru);
            $aktif = $baris->kedudukanPnsId === null
                || in_array($this->kode($baris->kedudukanPnsId), $kedudukanAktif, true)
                || stripos((string) $baris->kedudukanPnsNama, 'aktif') !== false;
            if ($nip !== '' && $aktif) {
                $data[$nip] = $this->petakanSiasn($baris);
            }
        }

        return $data;
    }

    private function petakanSiasn(object $b): array
    {
        $ref = $this->referensi();
        $ruang = $ref['ruang_bkn'][(string) $b->golRuangAkhirId] ?? $b->golRuangAkhir;
        $jenisKelamin = strtoupper((string) $b->jenisKelamin);

        return [
            'nama' => $b->nama,
            'unit_kerja' => $b->unorNama,
            'status' => $this->statusSiasn($b),
            'jk' => $jenisKelamin,
            'ruang' => $ruang,
            'opd' => $ref['opd_bkn'][(string) $b->unorId] ?? self::TANPA_OPD,
            'nilai' => [
                'id_bkn' => $b->id,
                'nama' => $b->nama,
                'tempat_lahir' => $b->tempatLahir,
                'tanggal_lahir' => $this->tanggal($b->tglLahir),
                'jenis_kelamin' => self::LABEL_JENIS_KELAMIN[$jenisKelamin] ?? $b->jenisKelamin,
                'agama' => $b->agama,
                'status_kepegawaian' => $this->statusSiasn($b),
                'tmt_cpns' => $this->tanggal($b->tmtCpns),
                'tmt_pns' => $this->tanggal($b->tmtPns),
                'golongan' => $b->golRuangAkhir,
                'tmt_golongan' => $this->tanggal($b->tmtGolAkhir),
                'jenis_jabatan' => $b->jenisJabatan,
                'jabatan' => $b->jabatanNama,
                'unit_kerja' => $b->unorNama,
                'pendidikan' => $b->pendidikanTerakhirNama,
            ],
            'id' => [
                'jenis_kelamin' => $jenisKelamin ?: null,
                'agama' => $b->agamaId,
                'golongan' => $b->golRuangAkhirId,
                'jenis_jabatan' => $b->jenisJabatanId,
                'jabatan' => $b->jabatanStrukturalId ?: ($b->jabatanFungsionalId ?: $b->jabatanFungsionalUmumId),
                'unit_kerja' => $b->unorId,
                'pendidikan' => $b->pendidikanTerakhirId,
            ],
        ];
    }

    /**
     * Status kepegawaian SIASN, mengikuti pembagian SIASN:
     * KEDUDUKAN HUKUM ID 71 = PPPK, 101 = PPPK PW (paruh waktu), selain itu ASN PNS yang dipecah dari
     * STATUS CPNS PNS: P = PNS, C = CPNS. (STATUS CPNS PNS juga berisi P untuk PPPK, jadi kedudukan dicek lebih dulu.)
     * Hanya untuk tampilan dan perbandingan, nilai asli di siasn_data_utama tidak diubah.
     */
    private function statusSiasn(object $b): string
    {
        $statusKedudukan = self::STATUS_PER_KEDUDUKAN_BKN[$this->kode($b->kedudukanPnsId)] ?? null;
        if ($statusKedudukan) {
            return $statusKedudukan;
        }

        $status = $this->teks($b->statusPegawai);

        return match ($status) {
            'P', 'PNS' => 'PNS',
            'C', 'CPNS' => 'CPNS',
            default => $status,
        };
    }

    // NIP pegawai aktif SIMPEG (key array), untuk memastikan cadangan pencocokan lewat ID BKN tidak mengambil
    // data SIASN yang sebenarnya milik pegawai lain berdasarkan NIP
    private function nipSimpegAktif(): array
    {
        $nip = [];
        foreach (DB::connection('mysql_second')->table('tbl_pegawai')
            ->where('id_satuan_organisasi', '>', 0)
            ->whereIn('id_kedudukan_kepegawaian', self::KEDUDUKAN_AKTIF)
            ->select('nip')
            ->cursor() as $baris) {
            $nip[$this->nip($baris->nip)] = true;
        }

        return $nip;
    }

    private function tanggalTarikTerakhir(): ?Carbon
    {
        $terakhir = SiasnDataUtamaModel::query()->max('updated_at');

        return $terakhir ? Carbon::parse($terakhir) : null;
    }

    // ---------------------------------------------------------------- bantuan

    // referensi SIMPEG yang dipakai untuk memetakan ID BKN, dimuat sekali per request
    private function referensi(): array
    {
        if ($this->referensi !== null) {
            return $this->referensi;
        }

        $db = DB::connection('mysql_second');

        $pangkat = $db->table('ref_pangkat')->orderBy('urut')->get(['gol_ruang', 'id_pangkat_bkn']);
        $ruang = $pangkat->map(fn($p) => $this->ruang($p->gol_ruang))->filter()->unique()->values()->all();

        $skpd = $db->table('ref_skpd as sk')
            ->leftJoin('ref_skpd as induk', DB::raw('LEFT(sk.kode_skpd, 3)'), '=', 'induk.kode_skpd')
            ->whereNotNull('sk.id_bkn')->where('sk.id_bkn', '<>', '')
            ->get(['sk.id_bkn', 'sk.skpd', 'induk.skpd as opd']);

        return $this->referensi = [
            'ruang' => $ruang,
            'ruang_bkn' => $pangkat->pluck('gol_ruang', 'id_pangkat_bkn')->mapWithKeys(fn($gol, $id) => [(string) $id => $gol])->all(),
            'opd_bkn' => $skpd->mapWithKeys(fn($s) => [(string) $s->id_bkn => $s->opd ?: $s->skpd])->all(),
            'kedudukan_aktif_bkn' => $db->table('ref_kedudukan_kepegawaian')
                ->whereIn('id_kedudukan_kepegawaian', self::KEDUDUKAN_AKTIF)
                ->pluck('id_kedudukan_pns_bkn')->map(fn($id) => $this->kode($id))->all(),
        ];
    }

    private function tambahRekap(array &$rekap, string $sisi, array $data): void
    {
        $tambah = function (string $kelompok, ?string $kategori) use (&$rekap, $sisi) {
            $kategori = $kategori ?: 'Tidak diketahui';
            $rekap[$kelompok][$kategori] ??= ['simpeg' => 0, 'siasn' => 0];
            $rekap[$kelompok][$kategori][$sisi]++;
        };

        $tambah('status', $data['status']);
        $tambah('jenis_kelamin', self::LABEL_JENIS_KELAMIN[$data['jk']] ?? null);
        $tambah('opd', $data['opd']);
        if (in_array($data['status'], ['PNS', 'CPNS'], true) && $this->ruang($data['ruang']) !== null) {
            $tambah('ruang', $this->ruang($data['ruang']));
        }
    }

    private function ringkasPegawai(string $nip, array $data): array
    {
        return [
            'nip' => $nip,
            'nama' => $data['nama'],
            'status' => $data['nilai']['status_kepegawaian'],
            'unit_kerja' => $data['unit_kerja'],
        ];
    }

    // bandingkan lewat ID BKN bila kedua sisi punya, selain itu lewat teks
    private function sama(array $simpeg, array $siasn, string $key): bool
    {
        $idSimpeg = $simpeg['id'][$key] ?? null;
        $idSiasn = $siasn['id'][$key] ?? null;
        if ($idSimpeg !== null && $idSimpeg !== '' && $idSiasn !== null && $idSiasn !== '') {
            return strcasecmp(trim((string) $idSimpeg), trim((string) $idSiasn)) === 0;
        }

        return $this->teks($simpeg['nilai'][$key] ?? null) === $this->teks($siasn['nilai'][$key] ?? null);
    }

    // golongan/ruang PNS dengan penulisan seragam: "iii/C " -> "III/c", selain format I/a s.d. IV/e -> null
    private function ruang(?string $golRuang): ?string
    {
        return preg_match('/^(IV|III|II|I)\s*\/\s*([a-e])$/i', trim((string) $golRuang), $m)
            ? strtoupper($m[1]) . '/' . strtolower($m[2])
            : null;
    }

    // kode BKN tanpa nol di depan: SIASN menulis "01", referensi SIMPEG menulis "1"
    private function kode($kode): string
    {
        $asli = trim((string) $kode);
        $tanpaNol = ltrim($asli, '0');

        return $tanpaNol === '' && $asli !== '' ? '0' : $tanpaNol;
    }

    private function nip($nip): string
    {
        return preg_replace('/\D/', '', (string) $nip);
    }

    private function teks($nilai): string
    {
        return mb_strtoupper(preg_replace('/\s+/', ' ', trim((string) $nilai)));
    }

    // tanggal dari database (Y-m-d) atau API (dd-mm-yyyy) -> dd-mm-yyyy
    private function tanggal($nilai): ?string
    {
        $nilai = trim((string) $nilai);
        if ($nilai === '' || str_starts_with($nilai, '0000')) {
            return null;
        }

        $ymd = preg_match('/^\d{2}-\d{2}-\d{4}$/', $nilai) ? SiasnDataUtamaModel::tanggalDariApi($nilai) : substr($nilai, 0, 10);
        if (!$ymd || !preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $ymd, $m) || !checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            return null;
        }

        return "{$m[3]}-{$m[2]}-{$m[1]}";
    }
}
