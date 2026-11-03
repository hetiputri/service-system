<?php

namespace App\Imports;

use App\Models\SiasnDataUtamaModel;
use App\Models\SiasnImportLogModel;
use App\Services\Integrasi\IntegrasiSiasnService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use RuntimeException;
use Throwable;

/**
 * Import file export SIASN (CSV/Excel) ke siasn_data_utama, dibaca per blok supaya hemat memori.
 * Baris pertama file adalah judul kolom; judul dicocokkan ke nama kolom tabel tanpa membedakan
 * huruf besar/kecil, spasi, garis bawah atau tanda baca ("NIP BARU", "nip_baru" dan "nipBaru" sama).
 *
 * StringValueBinder: isi CSV dibaca apa adanya sebagai teks (tanpa ini NIP berubah menjadi angka).
 * Sel Excel tetap dibaca sesuai tipenya, sehingga NIP berformat angka di Excel tetap terdeteksi.
 */
class SiasnDataUtamaImport extends StringValueBinder implements ToCollection, WithChunkReading, WithCustomValueBinder
{
    // judul kolom export SIASN yang namanya berbeda dari kolom tabel (sudah dinormalisasi => kolom tabel)
    private const ALIAS = [
        'pnsid' => 'id',
        'idpns' => 'id',
        'idasn' => 'id',
        'nip' => 'nipBaru',
        'nip18' => 'nipBaru',
        'tempatlahirnama' => 'tempatLahir',
        'tanggallahir' => 'tglLahir',
        'agamanama' => 'agama',
        'jeniskawinnama' => 'statusPerkawinan',
        'nomorhp' => 'noHp',
        'npwpnomor' => 'noNpwp',
        'kedudukanhukumid' => 'kedudukanPnsId',
        'kedudukanhukumnama' => 'kedudukanPnsNama',
        'statuscpnspns' => 'statusPegawai',
        'kartuasnvirtual' => 'kartuAsn',
        'tanggalskcpns' => 'tglSkCpns',
        'tanggalskpns' => 'tglSkPns',
        'golawalid' => 'golRuangAwalId',
        'golawalnama' => 'golRuangAwal',
        'golakhirid' => 'golRuangAkhirId',
        'golakhirnama' => 'golRuangAkhir',
        'tmtgolongan' => 'tmtGolAkhir',
        'golongan' => 'golRuangAkhir',
        'golonganruang' => 'golRuangAkhir',
        'jenisjabatannama' => 'jenisJabatan',
        'jabatanid' => self::KOLOM_JABATAN_ID,
        'jabatan' => 'jabatanNama',
        'tingkatpendidikanid' => 'tkPendidikanTerakhirId',
        'tingkatpendidikannama' => 'tkPendidikanTerakhir',
        'pendidikanid' => 'pendidikanTerakhirId',
        'pendidikannama' => 'pendidikanTerakhirNama',
        'lokasikerjanama' => 'lokasiKerja',
        'unitorganisasi' => 'unorNama',
        'isvalidnik' => 'validNik',
        'eselonnama' => 'eselon',
    ];

    // kolom export yang sengaja tidak disimpan (waktu buat/ubah milik SIASN, bukan waktu import)
    private const ABAIKAN = ['createdat', 'updatedat'];

    // JABATAN ID di export berlaku untuk semua jenis jabatan, disimpan ke kolom sesuai JENIS JABATAN ID
    private const KOLOM_JABATAN_ID = '_jabatanId';
    private const KOLOM_PER_JENIS_JABATAN = [
        '1' => 'jabatanStrukturalId',
        '2' => 'jabatanFungsionalId',
        '4' => 'jabatanFungsionalUmumId',
    ];

    private const MAKS_DETAIL_GAGAL = 50;

    public int $berhasil = 0;
    public int $gagal = 0;
    public array $detailGagal = [];
    public array $kolomTidakDikenal = [];

    // hasil pengisian tbl_pegawai.id_pegawai_bkn di SIMPEG dari PNS ID (lihat isiIdBknSimpeg)
    public int $idBknDiisi = 0;
    public int $idBknBerbeda = 0;
    public int $idBknDilewati = 0;
    public array $detailIdBkn = [];

    // nomor kolom file => nama kolom tabel, terisi dari baris judul
    private ?array $petaKolom = null;
    private int $nomorBaris = 0;

    public function __construct(private SiasnImportLogModel $log)
    {
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $rows): void
    {
        $siapSimpan = [];
        $nomorBarisPerId = [];

        foreach ($rows as $row) {
            $row = $row->toArray();
            $this->nomorBaris++;

            if ($this->petaKolom === null) {
                $this->bacaJudul($row);
                continue;
            }
            if (count(array_filter($row, fn($isi) => $isi !== null && $isi !== '')) === 0) {
                continue; // baris kosong
            }

            $data = [];
            foreach ($this->petaKolom as $indeks => $kolom) {
                $data[$kolom] = $row[$indeks] ?? null;
            }

            // ketiga kolom selalu diisi (null bila bukan jenisnya) supaya semua baris dalam satu upsert punya kolom yang sama
            if (array_key_exists(self::KOLOM_JABATAN_ID, $data)) {
                $jenisJabatan = trim((string) ($data['jenisJabatanId'] ?? ''));
                foreach (self::KOLOM_PER_JENIS_JABATAN as $jenis => $kolomJabatan) {
                    $data[$kolomJabatan] ??= (string) $jenis === $jenisJabatan ? $data[self::KOLOM_JABATAN_ID] : null;
                }
            }

            // Excel hanya menyimpan 15 digit angka: NIP yang pernah tersimpan sebagai angka menjadi "...000"
            // atau "1.99E+17". NIP asli tidak berakhiran 000 karena 3 digit terakhir adalah nomor urut.
            $nip = (string) ($data['nipBaru'] ?? '');
            if (stripos($nip, 'E+') !== false || preg_match('/^\d{15}000$/', preg_replace('/\D/', '', $nip))) {
                $this->catatGagal($nip, 'NIP rusak karena tersimpan sebagai angka di Excel, ubah kolom NIP menjadi teks lalu export ulang');
                continue;
            }

            if (trim((string) ($data['id'] ?? '')) === '') {
                $this->catatGagal($nip, 'ID SIASN kosong');
                continue;
            }

            $baris = SiasnDataUtamaModel::siapkanBaris($data);
            if (!$baris) {
                $this->catatGagal($nip, 'NIP kosong atau bukan 18 digit');
                continue;
            }

            // ID yang muncul lebih dari sekali dalam satu blok: pakai baris terakhir
            $siapSimpan[$baris['id']] = $baris;
            $nomorBarisPerId[$baris['id']] = $this->nomorBaris;
        }

        $tersimpan = $this->simpan(array_values($siapSimpan), $nomorBarisPerId);
        $this->isiIdBknSimpeg($tersimpan);

        $this->log->update([
            'berhasil' => $this->berhasil,
            'gagal' => $this->gagal,
            'id_bkn_diisi' => $this->idBknDiisi,
            'id_bkn_berbeda' => $this->idBknBerbeda,
            'id_bkn_dilewati' => $this->idBknDilewati,
        ]);
    }

    private function bacaJudul(array $judul): void
    {
        $kolomTabel = [];
        foreach ((new SiasnDataUtamaModel)->getFillable() as $kolom) {
            $kolomTabel[$this->normalisasi($kolom)] = $kolom;
        }

        $this->petaKolom = [];
        foreach ($judul as $indeks => $nama) {
            $kunci = $this->normalisasi((string) $nama);
            if ($kunci === '' || in_array($kunci, self::ABAIKAN, true)) {
                continue;
            }
            $kolom = $kolomTabel[$kunci] ?? self::ALIAS[$kunci] ?? null;
            if ($kolom && !in_array($kolom, $this->petaKolom, true)) {
                $this->petaKolom[$indeks] = $kolom;
            } else {
                $this->kolomTidakDikenal[] = (string) $nama;
            }
        }

        foreach (['id' => 'ID (PNS ID)', 'nipBaru' => 'NIP'] as $wajib => $nama) {
            if (!in_array($wajib, $this->petaKolom, true)) {
                throw new RuntimeException("Kolom {$nama} tidak ditemukan di baris pertama file. Judul kolom yang terbaca: "
                    . implode(', ', array_slice(array_map('strval', $judul), 0, 15)));
            }
        }

        $this->log->update(['kolom_tidak_dikenal' => $this->kolomTidakDikenal]);
    }

    // mengembalikan baris yang berhasil tersimpan
    private function simpan(array $baris, array $nomorBarisPerId): array
    {
        if (!$baris) {
            return [];
        }

        $kolomUpdate = array_values(array_diff(array_keys($baris[0]), ['id']));

        try {
            SiasnDataUtamaModel::query()->upsert($baris, ['id'], $kolomUpdate);
            $this->berhasil += count($baris);

            return $baris;
        } catch (Throwable $e) {
            // satu baris bermasalah (mis. isian terlalu panjang) jangan menggagalkan seluruh blok
            $tersimpan = [];
            foreach ($baris as $satu) {
                try {
                    SiasnDataUtamaModel::query()->upsert([$satu], ['id'], $kolomUpdate);
                    $this->berhasil++;
                    $tersimpan[] = $satu;
                } catch (Throwable $e) {
                    // pesan MySQL saja (mis. "Data too long for column ..."), tanpa SQL lengkapnya
                    $alasan = $e instanceof QueryException ? ($e->errorInfo[2] ?? $e->getMessage()) : $e->getMessage();
                    $this->catatGagal($satu['nipBaru'], $alasan, $nomorBarisPerId[$satu['id']] ?? null);
                }
            }

            return $tersimpan;
        }
    }

    /**
     * Isi tbl_pegawai.id_pegawai_bkn (SIMPEG) yang masih kosong dengan PNS ID dari file, dicocokkan lewat NIP.
     * Hanya kolom id_pegawai_bkn yang diubah (tanpa updated_at). id_pegawai_bkn yang sudah terisi tidak diubah:
     * bila berbeda dengan PNS ID hanya dilaporkan. NIP yang ada di lebih dari satu baris memakai pegawai aktif,
     * bila pegawai aktifnya bukan tepat satu orang maka dilewati dan dilaporkan.
     */
    private function isiIdBknSimpeg(array $baris): void
    {
        $pnsIdPerNip = [];
        foreach ($baris as $satu) {
            $pnsIdPerNip[$satu['nipBaru']] = $satu['id'];
        }
        if (!$pnsIdPerNip) {
            return;
        }

        try {
            $db = DB::connection('mysql_second');
            // key array NIP 18 digit berubah menjadi integer di PHP; MySQL membandingkan varchar dengan angka
            // sebesar itu sebagai float (presisi ~15 digit) sehingga NIP berbeda bisa dianggap sama, jadi wajib string
            $pegawaiPerNip = $db->table('tbl_pegawai')
                ->whereIn(DB::raw(IntegrasiSiasnService::sqlNipAngka('nip')), array_map('strval', array_keys($pnsIdPerNip)))
                ->get(['id_pegawai', 'nip', 'id_pegawai_bkn', 'id_kedudukan_kepegawaian', 'id_satuan_organisasi'])
                ->groupBy(fn($pegawai) => preg_replace('/\D/', '', (string) $pegawai->nip));

            foreach ($pnsIdPerNip as $nip => $pnsId) {
                $kandidat = $pegawaiPerNip->get((string) $nip);
                if (!$kandidat) {
                    continue; // NIP tidak ada di SIMPEG, tampil sebagai "hanya di SIASN" di dashboard
                }

                if ($kandidat->count() > 1) {
                    $aktif = $kandidat->filter(fn($pegawai) => $pegawai->id_satuan_organisasi > 0
                        && in_array((int) $pegawai->id_kedudukan_kepegawaian, IntegrasiSiasnService::KEDUDUKAN_AKTIF, true));
                    if ($aktif->count() !== 1) {
                        $this->idBknDilewati++;
                        $this->catatIdBkn($nip, "NIP ada di {$kandidat->count()} data tbl_pegawai, {$aktif->count()} di antaranya pegawai aktif");
                        continue;
                    }
                    $kandidat = $aktif;
                }

                $pegawai = $kandidat->first();
                $idSimpeg = trim((string) $pegawai->id_pegawai_bkn);

                if ($idSimpeg === '') {
                    $diubah = $db->table('tbl_pegawai')
                        ->where('id_pegawai', $pegawai->id_pegawai)
                        ->where(fn($q) => $q->whereNull('id_pegawai_bkn')->orWhere('id_pegawai_bkn', ''))
                        ->update(['id_pegawai_bkn' => $pnsId]);
                    $this->idBknDiisi += $diubah;
                } elseif (strcasecmp($idSimpeg, $pnsId) !== 0) {
                    $this->idBknBerbeda++;
                    $this->catatIdBkn($nip, 'id_pegawai_bkn SIMPEG berbeda dengan PNS ID SIASN', $idSimpeg, $pnsId);
                }
            }
        } catch (Throwable $e) {
            // kegagalan mengisi id_pegawai_bkn tidak menggagalkan import data SIASN
            $alasan = $e instanceof QueryException ? ($e->errorInfo[2] ?? $e->getMessage()) : $e->getMessage();
            $this->catatIdBkn(null, 'Gagal mengisi id_pegawai_bkn: ' . mb_substr($alasan, 0, 150));
        }
    }

    private function catatIdBkn($nip, string $keterangan, ?string $idSimpeg = null, ?string $idSiasn = null): void
    {
        if (count($this->detailIdBkn) < self::MAKS_DETAIL_GAGAL) {
            $this->detailIdBkn[] = array_filter([
                'nip' => $nip !== null ? (string) $nip : null,
                'keterangan' => $keterangan,
                'id_simpeg' => $idSimpeg,
                'id_siasn' => $idSiasn,
            ], fn($nilai) => $nilai !== null);
        }
    }

    private function catatGagal($nip, string $alasan, ?int $nomorBaris = null): void
    {
        $this->gagal++;
        if (count($this->detailGagal) < self::MAKS_DETAIL_GAGAL) {
            $this->detailGagal[] = [
                'baris' => $nomorBaris ?? $this->nomorBaris,
                'nip' => is_scalar($nip) ? (string) $nip : null,
                'alasan' => mb_substr($alasan, 0, 200),
            ];
        }
    }

    private function normalisasi(string $nama): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower($nama));
    }
}
