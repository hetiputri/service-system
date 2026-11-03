<?php

namespace App\Http\Controllers\Integrasi;

use App\Http\Controllers\Controller;
use App\Jobs\ImportSiasnDataUtama;
use App\Models\SiasnImportLogModel;
use App\Repositories\Integrasi\IntegrasiRepo;
use App\Services\Integrasi\IntegrasiSiasnService;
use App\Services\Integrasi\RiwayatSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;


class IntegrasiController extends Controller
{
    // sync ke SIASN (kirim data SIMPEG ke SIASN) dinonaktifkan sementara, hanya sync ke SIMPEG yang dipakai
    public const SYNC_KE_SIASN_AKTIF = false;

    protected $integrasi;
    protected $siasn;
    protected $riwayatSync;
    public function __construct(IntegrasiRepo $integrasi, IntegrasiSiasnService $siasn, RiwayatSyncService $riwayatSync)
    {
        $this->integrasi = $integrasi;
        $this->siasn = $siasn;
        $this->riwayatSync = $riwayatSync;
    }

    public function integrate($isian)
    {
        $result = $this->integrasi->apisiasn($isian);
        return $result;
    }

    public function dashboard()
    {
        $ringkasan = $this->siasn->ringkasanTersimpan();
        $rekap = $ringkasan['rekap'];

        $keBaris = fn(array $data) => collect($data)
            ->map(fn($jumlah, $kategori) => ['kategori' => (string) $kategori] + $jumlah)
            ->values()->all();

        // ruang dikelompokkan per golongan, subtotal golongan dihitung dari rinciannya
        $perGolongan = collect($rekap['ruang'])
            ->map(fn($jumlah, $ruang) => ['kategori' => (string) $ruang] + $jumlah)
            ->groupBy(fn($item) => 'Golongan ' . strstr($item['kategori'], '/', true))
            ->map(fn($ruang, $golongan) => [
                'kategori' => $golongan,
                'simpeg' => $ruang->sum('simpeg'),
                'siasn' => $ruang->sum('siasn'),
                'ruang' => $ruang->values()->all(),
            ])->values()->all();

        $perStatus = $keBaris($rekap['status']);
        $perJenisKelamin = $keBaris($rekap['jenis_kelamin']);
        $perUnitKerja = $keBaris($rekap['opd']);

        $semuaKategori = array_merge($perStatus, $keBaris($rekap['ruang']), $perJenisKelamin, $perUnitKerja);

        return view('pages.integrasi.dashboard', [
            'importTerakhir' => $this->importTerakhir(),
            'siasnTersedia' => $ringkasan['siasnTersedia'],
            'adaDataSiasn' => $ringkasan['adaDataSiasn'],
            'tanggalTarik' => $ringkasan['tanggalTarik'],
            'totalSimpeg' => array_sum(array_column($perStatus, 'simpeg')),
            'totalSiasn' => array_sum(array_column($perStatus, 'siasn')),
            'kategoriBerbeda' => count(array_filter($semuaKategori, fn($item) => $item['simpeg'] != $item['siasn'])),
            'perGolongan' => $perGolongan,
            'perUnitKerja' => $perUnitKerja,
            'perbandingan' => [
                'Status Kepegawaian' => $perStatus,
                'Jenis Kelamin' => $perJenisKelamin,
            ],
            'jumlahSelisih' => [
                'hanya_siasn' => count($ringkasan['hanyaDiSiasn']),
                'hanya_simpeg' => count($ringkasan['hanyaDiSimpeg']),
                'atribut_berbeda' => count($ringkasan['atributBerbeda']),
            ],
        ]);
    }

    /**
     * Upload file export SIASN (CSV/Excel), diproses di background oleh job ImportSiasnDataUtama.
     */
    public function importSiasn(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:51200'],
        ], [
            'file.mimes' => 'File harus berformat CSV atau Excel (xlsx/xls).',
            'file.max' => 'Ukuran file maksimal 50 MB.',
        ]);

        if (!$this->siasn->siasnTersedia()) {
            return back()->with('error', 'Database SIASN belum bisa diakses. Periksa koneksi mysql_third dan migration siasn_data_utama.');
        }
        if ($this->importTerakhir()?->sedangBerjalan()) {
            return back()->with('error', 'Masih ada import yang sedang diproses. Tunggu sampai selesai sebelum upload file baru.');
        }

        $file = $request->file('file');
        $namaSimpan = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs('siasn-import', $namaSimpan, 'local');

        $log = SiasnImportLogModel::create([
            'nama_file' => $file->getClientOriginalName(),
            'path' => $path,
            'status' => SiasnImportLogModel::STATUS_MENUNGGU,
            'id_user' => $request->user()?->getKey(),
            'nama_user' => $request->user()?->nama,
        ]);

        ImportSiasnDataUtama::dispatch($log->id);

        return redirect()->route('integrasi.siasn.dashboard')
            ->with('success', 'File ' . $log->nama_file . ' diterima dan sedang diproses di background.');
    }

    // satu halaman daftar pegawai untuk tab "Rincian Data Berbeda" di dashboard
    // sumber data DataTables server-side untuk tab "Rincian Data Berbeda" di dashboard
    public function daftarPegawai(Request $request)
    {
        $validated = $request->validate([
            'jenis' => ['required', Rule::in(['hanyaDiSiasn', 'hanyaDiSimpeg', 'atributBerbeda'])],
            'draw' => ['nullable', 'integer'],
            'start' => ['nullable', 'integer', 'min:0'],
            'length' => ['nullable', 'integer', 'min:1', 'max:500'],
            'search.value' => ['nullable', 'string', 'max:100'],
            'order.0.column' => ['nullable', 'integer', 'min:0'],
            'order.0.dir' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        // nama data kolom yang diurutkan, hanya kolom yang memang boleh diurutkan
        $indeksUrut = $validated['order'][0]['column'] ?? null;
        $kolomUrut = $indeksUrut !== null ? $request->input("columns.$indeksUrut.data") : null;
        $kolomUrut = in_array($kolomUrut, ['nip', 'nama', 'atribut'], true) ? $kolomUrut : null;

        $hasil = $this->siasn->daftarPegawai(
            $validated['jenis'],
            (string) ($validated['search']['value'] ?? ''),
            (int) ($validated['start'] ?? 0),
            (int) ($validated['length'] ?? 25),
            $kolomUrut,
            $validated['order'][0]['dir'] ?? 'asc'
        );

        // nomor urut berlanjut antar halaman
        $mulai = (int) ($validated['start'] ?? 0);
        $data = array_map(fn($item, $i) => ['no' => $mulai + $i + 1] + $item, $hasil['data'], array_keys($hasil['data']));

        return response()->json([
            'draw' => (int) ($validated['draw'] ?? 0),
            'recordsTotal' => $hasil['total'],
            'recordsFiltered' => $hasil['terfilter'],
            'data' => $data,
        ]);
    }

    // progres import terakhir, dipanggil berkala dari dashboard selama import berjalan
    public function statusImport()
    {
        $log = $this->importTerakhir();
        if (!$log) {
            return response()->json(null);
        }

        $diproses = $log->berhasil + $log->gagal;

        return response()->json([
            'status' => $log->status,
            'berjalan' => $log->sedangBerjalan(),
            'total' => $log->total_baris,
            'diproses' => $diproses,
            'persen' => $log->total_baris > 0 ? min(100, (int) floor($diproses / $log->total_baris * 100)) : null,
            'pesan' => $log->pesan,
        ]);
    }

    private function importTerakhir(): ?SiasnImportLogModel
    {
        try {
            return SiasnImportLogModel::query()->latest('id')->first();
        } catch (Throwable $e) {
            return null; // tabel siasn_import_log belum di-migrate
        }
    }

    // sumber data DataTables riwayat sync satu pegawai di halaman detail
    public function riwayatSyncData(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'regex:/^\d{1,30}$/'],
            'draw' => ['nullable', 'integer'],
            'start' => ['nullable', 'integer', 'min:0'],
            'length' => ['nullable', 'integer', 'min:1', 'max:500'],
            'search.value' => ['nullable', 'string', 'max:100'],
            'order.0.dir' => ['nullable', Rule::in(['asc', 'desc'])],
            'dari' => ['nullable', 'date_format:Y-m-d'],
            'sampai' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $hasil = $this->riwayatSync->tabel(
            $validated['nip'],
            (string) ($validated['search']['value'] ?? ''),
            $validated['dari'] ?? null,
            $validated['sampai'] ?? null,
            (int) ($validated['start'] ?? 0),
            (int) ($validated['length'] ?? 25),
            $validated['order'][0]['dir'] ?? 'desc'
        );

        return response()->json([
            'draw' => (int) ($validated['draw'] ?? 0),
            'recordsTotal' => $hasil['total'],
            'recordsFiltered' => $hasil['terfilter'],
            'data' => $hasil['data'],
        ]);
    }

    public function detailPegawai(Request $request)
    {
        $nip = preg_replace('/\D/', '', (string) $request->query('nip'));
        $pegawai = $nip ? $this->pegawaiDenganSimulasi($nip) : null;

        $kelompok = [];
        $berbeda = [];
        if ($pegawai) {
            $berbeda = $this->siasn->bandingkan($pegawai['simpeg'], $pegawai['siasn']);
            $keyBerbeda = array_column($berbeda, 'key');

            foreach (IntegrasiSiasnService::ATRIBUT_PEGAWAI as $judul => $atribut) {
                foreach ($atribut as $key => $label) {
                    $kelompok[$judul][] = [
                        'key' => $key,
                        'label' => $label,
                        'simpeg' => $pegawai['simpeg']['nilai'][$key] ?? null,
                        'siasn' => $pegawai['siasn']['nilai'][$key] ?? null,
                        'berbeda' => in_array($key, $keyBerbeda),
                    ];
                }
            }
        }

        return view('pages.integrasi.detail_pegawai', [
            'nip' => $nip,
            'pegawai' => $pegawai,
            'kelompok' => $kelompok,
            'berbeda' => $berbeda,
            'jumlahAtribut' => count(IntegrasiSiasnService::semuaKeyAtribut()),
            'bolehUpdate' => $request->user()?->hasAnyPermission('integrasi-siasn') ?? false,
            'adaSimulasi' => collect(session('simulasi_integrasi_siasn', []))->contains(fn($sisi) => !empty($sisi['siasn'])),
            'kolomSyncSimpeg' => array_keys(IntegrasiSiasnService::KOLOM_SYNC_SIMPEG),
            'riwayatTersedia' => $this->riwayatSync->tersedia(),
        ]);
    }

    /**
     * arah = 'siasn'  : nilai SIMPEG dipakai untuk memperbarui SIASN
     * arah = 'simpeg' : nilai SIASN dipakai untuk memperbarui SIMPEG
     */
    public function updateData(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'digits:18'],
            'arah' => ['required', Rule::in(['siasn', 'simpeg'])],
            'atribut' => ['required', 'array', 'min:1'],
            'atribut.*' => ['required', 'string', Rule::in(IntegrasiSiasnService::semuaKeyAtribut())],
        ]);

        $nip = $validated['nip'];
        $arah = $validated['arah'];

        // data SIASN disimpan langsung ke SIMPEG (tbl_pegawai), dicatat di siasn_sync_log
        if ($arah === 'simpeg') {
            try {
                $jumlah = $this->siasn->syncKeSimpeg($nip, $validated['atribut'], $request->user());
            } catch (RuntimeException $e) {
                return back()->with('error', $e->getMessage());
            }

            return redirect()->route('integrasi.siasn.pegawai', ['nip' => $nip])->with('success', $jumlah > 0
                ? $jumlah . ' data berhasil disimpan ke SIMPEG (tbl_pegawai).'
                : 'Tidak ada perubahan, data SIMPEG sudah sama dengan SIASN.');
        }

        $pegawai = $this->pegawaiDenganSimulasi($nip);
        abort_unless($pegawai, 404);

        if (!self::SYNC_KE_SIASN_AKTIF) {
            return back()->with('error', 'Sync ke SIASN belum diaktifkan.');
        }

        $asal = $arah === 'siasn' ? 'simpeg' : 'siasn';
        if (!$pegawai[$asal]) {
            return back()->with('error', 'Data pegawai tidak ada di ' . strtoupper($asal) . ', tidak ada nilai yang bisa dipakai.');
        }
        if ($arah === 'siasn' && !$pegawai['siasn']) {
            return back()->with('error', 'Pegawai belum terdaftar di SIASN. Penambahan ASN baru ke SIASN tidak bisa dilakukan dari sini.');
        }

        // TODO: ganti simulasi ini dengan pemanggilan API SIASN (arah siasn) atau penyimpanan ke tabel SIMPEG (arah simpeg)
        $simulasi = session('simulasi_integrasi_siasn', []);
        foreach ($validated['atribut'] as $key) {
            $simulasi[$nip][$arah][$key] = [
                'nilai' => $pegawai[$asal]['nilai'][$key] ?? null,
                'id' => $pegawai[$asal]['id'][$key] ?? null,
            ];
        }
        session(['simulasi_integrasi_siasn' => $simulasi]);

        return redirect()->route('integrasi.siasn.pegawai', ['nip' => $nip])
            ->with('success', count($validated['atribut']) . ' data berhasil di-sync ke ' . strtoupper($arah) . ' (simulasi).');
    }

    public function resetSimulasi()
    {
        session()->forget('simulasi_integrasi_siasn');

        return back()->with('success', 'Simulasi sync dikembalikan ke data awal.');
    }

    // data pegawai dari database, ditimpa hasil simulasi sync ke SIASN yang tersimpan di session (lihat updateData).
    // Sync ke SIMPEG sudah tersimpan sungguhan, sisa simulasi lama ke SIMPEG di session diabaikan.
    private function pegawaiDenganSimulasi(string $nip): ?array
    {
        $pegawai = $this->siasn->pegawai($nip);
        $simulasi = array_intersect_key(session('simulasi_integrasi_siasn', [])[$nip] ?? [], ['siasn' => true]);
        if (!$pegawai || !$simulasi) {
            return $pegawai;
        }

        foreach ($simulasi as $sisi => $atribut) {
            $asal = $sisi === 'siasn' ? 'simpeg' : 'siasn';
            // pegawai yang belum ada di sisi tujuan dibuat dari data sisi asal
            $pegawai[$sisi] ??= ['nilai' => [], 'id' => []] + $pegawai[$asal];
            foreach ($atribut as $key => $isi) {
                if (!is_array($isi)) {
                    continue; // format simulasi lama (data statis)
                }
                $pegawai[$sisi]['nilai'][$key] = $isi['nilai'];
                $pegawai[$sisi]['id'][$key] = $isi['id'];
            }
        }

        return $pegawai;
    }
}
