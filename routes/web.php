<?php

use App\Http\Controllers\Pensiun\AdminPensiunController;
use App\Http\Controllers\Pensiun\DokumenController;
use App\Http\Controllers\Pensiun\PensiunController;
use App\Http\Controllers\Pensiun\LaporanController;
use App\Http\Controllers\Cuti\CutiController;
use App\Http\Controllers\Cuti\CutiLanjutan;
use App\Http\Controllers\Cuti\CutiProsesAdmin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Integrasi\IntegrasiController;
use App\Http\Controllers\Laporan\LaporanDashController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Pensiun\SupervisiController;
// use App\Http\Controllers\Otentikasi\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Referensi\ReferensiController;
use App\Http\Controllers\RolePermission\PermissionLists;
use App\Http\Controllers\RolePermission\RoleLists;
use App\Http\Controllers\Sinkronisasi\SinkronData;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('loginPost');
Route::get('/logout', function () {
    return redirect('/login');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('simpeg.auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::middleware('permission:r-cari-data-pegawai')->group(function () {
        Route::get('/cari-pegawai', function () {
            return view('pages.cari-pegawai');
        });
        Route::get('/master/{type}', [ProfileController::class, 'master']);
        Route::post('/simpeg/ajax-search', [ProfileController::class, 'ajaxSearch'])
            ->name('simpeg.ajax.search');
        Route::get('/pegawai/{id}/profile', [ProfileController::class, 'show'])
            ->name('pegawai.profile');
    });
    Route::get('/pengajuan-pensiun', [PensiunController::class, 'index']);
    Route::get('/pensiun/riwayat', [PensiunController::class, 'riwayat']);
    Route::post(
        '/pengajuan/pensiun/submit',
        [PensiunController::class, 'submit']
    )->name('pengajuan.pensiun.submit');
    Route::get('/detail-pensiun/{id}', [PensiunController::class, 'detail']);

    //     Route::post('/pengajuan/{id}/upload-dokumen',
    //     [PengajuanLayananController::class, 'uploadDokumenStep2']
    // )->name('pengajuan.upload.dokumen');

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/pengajuan/pensiun/set-jenis', [PensiunController::class, 'setJenis']);
    Route::post('/pengajuan/pensiun/update-step', [PensiunController::class, 'updateStep']);

    Route::middleware('permission:cru-surat-disiplin|r-surat-disiplin')->group(function () {
        Route::get('/generate-surat-disiplin', [AdminPensiunController::class, 'generateDisiplin']);
    });
    Route::middleware('permission:cru-surat-pidana|r-surat-pidana')->group(function () {
        Route::get('/generate-surat-pidana', [AdminPensiunController::class, 'generatePidana']);
    });
    Route::middleware('permission:cru-surat-disiplin')->group(function () {
        Route::post('/store-surat-disiplin', [AdminPensiunController::class, 'storeDisiplin']);
    });
    Route::middleware('permission:cru-surat-pidana')->group(function () {
        Route::post('/save-surat-pidana', [AdminPensiunController::class, 'storePidana']);
    });
    Route::middleware('permission:cru-dokumen-pensiun')->group(function () {
        Route::post('/admin-pensiun/dokumen/upload', [AdminPensiunController::class, 'uploadDokumen']);
    });
    Route::middleware('permission:cru-sk-pensiun')->group(function () {
        Route::post('/pensiun/upload-sk', [AdminPensiunController::class, 'uploadSk'])
            ->name('pensiun.upload.sk');
    });

    Route::get('/dokumen/generate', [DokumenController::class, 'generate']);
    Route::post('/dokumen/store', [DokumenController::class, 'store']);

    Route::get('/pensiun/preview-sk/{id}', [AdminPensiunController::class, 'previewSk'])
        ->name('pensiun.preview.sk');
    Route::get('/pensiun/preview/{type}/{id}', [AdminPensiunController::class, 'preview'])
        ->name('pensiun.preview');

    // ✅ Bisa diakses Admin OPD & BKD
    Route::middleware('permission:pemroses-pensiun-pd|pemroses-pensiun-bkd')->group(function () {
        Route::get('/admin-pensiun', [AdminPensiunController::class, 'index']);
        Route::get('/admin-pensiun-detail/{id}', [AdminPensiunController::class, 'detail']);
        Route::get('/admin-pensiun-data', [AdminPensiunController::class, 'data'])->name('admin.pensiun.data');
        Route::post('/admin-pensiun/aksi', [AdminPensiunController::class, 'aksi']);
        Route::post('/admin-pensiun/submit-final', [AdminPensiunController::class, 'submitFinal']);
        Route::post('/admin-pensiun/dokumen/upload', [AdminPensiunController::class, 'uploadDokumen']);
        //Route::post('/admin-pensiun/proses', [AdminPensiunController::class, 'proses']);
    });

    Route::get('generate-docx', [AdminPensiunController::class, 'GenerateDocx']);

    Route::middleware('permission:pemroses-pensiun-bkd')->group(function () {
        Route::post('/admin-pensiun/bkd-proses', [AdminPensiunController::class, 'lanjutProses'])->name('bkd.proses');
        Route::match(['get', 'post'], '/rekap-pensiun', [LaporanController::class, 'index'])->name('rekap.pensiun');
    });

    Route::middleware('permission:spv-pensiun')->group(function () {
        Route::match(['get', 'post'], '/supervisi-pensiun', [SupervisiController::class, 'index'])->name('pensiun.spv');
        Route::post('/spv-pensiun/assign-admin', [SupervisiController::class, 'assignAdmin'])
            ->name('supervisi-pensiun.assign-admin');
        Route::get('/spv-pensiun/data/{id}', [SupervisiController::class, 'dataDetail']);
        Route::get('/spv-pensiun/admin-hapus/{id}', [SupervisiController::class, 'hapusData']);
    });

    Route::middleware('permission:dashboard-pensiun')->group(function () {
        Route::get('/pensiun/dashboard', [LaporanController::class, 'ringkasan']);
    });

    //Route::get('/admin-pensiun', [AdminPensiunController::class, 'index']);
    //Route::get('/admin-pensiun-detail/{id}', [AdminPensiunController::class, 'detail']);
    //Route::post('/admin-pensiun/proses', [AdminPensiunController::class, 'proses']);
    //Route::get('/admin-data', [AdminPensiunController::class, 'data'])->name('admin.pensiun.data');
    //Route::post('/admin-pensiun/submit-final', [AdminPensiunController::class, 'submitFinal']);


    Route::middleware('role:AdminOpdPensiun')->group(function () {

        // Route::prefix('admin/opd')->group(function () {
        // });
    });

    Route::get('/pengajuan/pensiun/{id}/preview-dpcp', [PensiunController::class, 'previewDpcp'])->name('pensiun.preview.dpcp');

    Route::get('/pengajuan/pensiun/{id}/download-dpcp', [PensiunController::class, 'downloadDpcp'])->name('pensiun.download.dpcp');

    Route::middleware('permission:pendaftaran-akses')->group(function () {
        Route::get('/pengaturan-akses', [RoleLists::class, 'render'])->name('pengaturan.akses');
        Route::post('/role-tambah-edit', [RoleLists::class, 'createupdate'])->name('role.tambah.edit');
        Route::post('/role-edit', [RoleLists::class, 'update'])->name('role.edit');
        Route::get('/role-hapus/{id}', [RoleLists::class, 'delete'])->name('role.hapus');
        Route::post('/roletambahpermission', [RoleLists::class, 'AssignPermissionOnRole'])->name('assign.permission.role');
        Route::get('/role/permissionlist/{role_name}', [RoleLists::class, 'CekPermissionOnRole']);
        Route::get('/permission', [PermissionLists::class, 'render'])->name('permission');
        Route::post('/permission-tambah-edit', [PermissionLists::class, 'createupdate'])->name('permission.tambah.edit');
        Route::get('/permission-hapus/{id}', [PermissionLists::class, 'delete'])->name('permission.hapus');
    });

    Route::middleware('permission:pengaturan-akun')->group(function () {
        Route::get('/pengaturan-akun', [RoleLists::class, 'renderAkun'])->name('pengaturan.akun');
        Route::post('/penggunatambahedit', [RoleLists::class, 'Penggunatambahedit']);
        Route::get('/penggunahapus/{id_user}', [RoleLists::class, 'Penggunahapus']);
        Route::get('/rolepadapengguna/{id_user}', [RoleLists::class, 'CekRoleUser']);
    });
    Route::get('/rolepadapengguna/{id_user}', [RoleLists::class, 'CekRoleUser']);
    Route::post('/caripengguna', [RoleLists::class, 'Penggunasearch']);

    Route::middleware('permission:sinkronisasi-data')->group(function () {
        Route::get('/sinkron-data', [SinkronData::class, 'index']);
        Route::get('/sinkronisasi/{id}', [SinkronData::class, 'sinkron']);
    });

    Route::middleware('permission:integrasi-siasn')->group(function () {
        Route::get('/integrasi-siasn/dashboard', [IntegrasiController::class, 'dashboard'])->name('integrasi.siasn.dashboard');
        Route::post('/integrasi-siasn/import', [IntegrasiController::class, 'importSiasn'])->name('integrasi.siasn.import');
        Route::get('/integrasi-siasn/import/status', [IntegrasiController::class, 'statusImport'])->name('integrasi.siasn.import.status');
        Route::get('/integrasi-siasn/daftar', [IntegrasiController::class, 'daftarPegawai'])->name('integrasi.siasn.daftar');
        Route::get('/integrasi-siasn/pegawai', [IntegrasiController::class, 'detailPegawai'])->name('integrasi.siasn.pegawai');
        Route::get('/integrasi-siasn/riwayat-sync/data', [IntegrasiController::class, 'riwayatSyncData'])->name('integrasi.siasn.riwayat.data');
        Route::post('/integrasi-siasn/pegawai/update', [IntegrasiController::class, 'updateData'])->name('integrasi.siasn.update');
        Route::post('/integrasi-siasn/simulasi/reset', [IntegrasiController::class, 'resetSimulasi'])->name('integrasi.siasn.reset');
    });



    //Route::post('/admin-pensiun/aksi', [AdminPensiunController::class, 'aksi']);
    // Route::prefix('admin/opd')->group(function () {
    // });

    //     $response = Http::withToken($token)
    //         ->asForm()
    //         ->post(config('services.simpeg.url') . '/filter-data', $filters);

    //     return [
    //         'status' => $response->status(),
    //         'body' => $response->body()
    //     ];
    // });

    // bezetting
    require __DIR__ . '/bezetting.php';
    // daftar-pegawai
    require __DIR__ . '/daftar-pegawai.php';
    // Pejabat Struktural
    require __DIR__ . '/pejabat-struktural.php';
    // Mutasi
    require __DIR__ . '/mutasi.php';

    // pegawai
    Route::get('/pegawai', [PegawaiController::class, 'searchWithFilters']);
    // jabatan
    Route::get('/jabatan', [ReferensiController::class, 'searchJabatanWithFilters']);
});

Route::get('/dokumen/preview/{id}', [DokumenController::class, 'preview'])
    ->name('dokumen.preview');

Route::post(
    '/pengajuan/pensiun/save-dpcp-signature',
    [PensiunController::class, 'saveSignature']
);

Route::get(
    '/dokumen-pensiun/view',
    [PensiunController::class, 'viewDokumen']
)->name('dokumen.view');

Route::get(
    '/pengajuan/pensiun/persyaratan',
    [PensiunController::class, 'getPersyaratan']
)->name('pengajuan.pensiun.persyaratan');

// Route::post('/pengajuan/pensiun/upload-dokumen',
//     [PensiunController::class,'uploadDokumen']);
Route::post(
    '/pengajuan/pensiun/upload-dokumen',
    [PensiunController::class, 'uploadDokumen']
)->name('pengajuan.pensiun.upload.dokumen');
Route::post(
    '/pengajuan/pensiun/hapus-dokumen',
    [PensiunController::class, 'hapusDokumen']
);

Route::get(
    '/file/show',
    [DokumenController::class, 'show']
)->name('file.show');

Route::get('/integrasi/{isian}', [IntegrasiController::class, 'integrate']);

Route::middleware('permission:pengajuan-cuti')->group(function () {
    Route::get('/pengajuan-cuti', [CutiController::class, 'index'])->name('pengajuan.cuti');
    Route::post('/pengajuan/cuti/addupdate', [CutiController::class, 'setJenis'])->name('pengajuan.cuti.set-jenis');
    Route::get('/pengajuan/cuti/print/{id}/{ttd}', [CutiController::class, 'printFormulir'])->name('cetak.pdf.cuti');
    Route::post('/pengajuan/cuti/upload', [CutiController::class, 'uploadDokumen']);
    Route::get('/pengajuan/cuti/lihat-upload', [CutiController::class, 'LihatDokumen'])->name('cuti.lihat-upload');
    Route::get('/pengajuan/cuti/selesai/{id}', [CutiController::class, 'UsulanSelesai'])->name('cuti.usulan.selesai');
    Route::get('/pengajuan/cuti/hapus/{id}', [CutiController::class, 'HapusUsulan'])->name('cuti.usulan.hapus');

    Route::get('/cuti/riwayat-usulan', [CutiLanjutan::class, 'index'])->name('cuti.usulan.rekap');
    Route::get('/detail-cuti/{id}', [CutiLanjutan::class, 'detailCuti'])->name('cuti.detail');
});

Route::middleware('permission:proses-cuti')->group(function () {
    Route::get('/proses-utama-cuti', [CutiProsesAdmin::class, 'first'])->name('main.menu');
    Route::get('/proses-utama-cuti/hapus/{id}', [CutiProsesAdmin::class, 'hapusProsesUtama'])->name('main.proses.hapus');
    Route::post('/proses-cuti/tambah', [CutiProsesAdmin::class, 'tambahProses'])->name('tambah.proses.cuti');

    Route::get('/proses-cuti/pegawai/{id}', [CutiProsesAdmin::class, 'DaftarPegawai'])->name('daftar.pegawai.cuti');
    Route::post('/proses-cuti/pegawai-tambah', [CutiProsesAdmin::class, 'tambahDaftarPegawai'])->name('tambah.daftar.pegawai');
    Route::get('/proses-cuti', [CutiProsesAdmin::class, 'index'])->name('proses.cuti');
    Route::get('/proses-cuti/hapus/{id}', [CutiProsesAdmin::class, 'hapusProses'])->name('hapus.proses.cuti'); //hapus pegawai usulan
    Route::post('/pencarian/cuti-usulan', [CutiProsesAdmin::class, 'cariUsulan'])->name('cari.usulan');
    Route::get('/cuti/detail-usulan', [CutiProsesAdmin::class, 'detailUsulan'])->name('detail.usulan');
    Route::post('/cuti/usulan-tolak', [CutiProsesAdmin::class, 'tolakUsulan'])->name('tolak.usulan');
    Route::get('/cek-file-cuti/{id_pengajuan}', [CutiProsesAdmin::class, 'cekFile'])->name('cek.file.cuti');
    Route::post('/sk-cuti/upload', [CutiProsesAdmin::class, 'uploadSKFinal'])->name('upload.sk.final');
    Route::get('/sk-cuti/lihat-upload', [CutiProsesAdmin::class, 'LihatSK'])->name('cuti.lihat-sk');
    Route::post('/cuti-unduh-files', [CutiProsesAdmin::class, 'UnduhFiles'])->name('cuti.unduh-sp');

    Route::match(['get', 'post'], '/rekap-hasil-cuti', [CutiProsesAdmin::class, 'rekapCuti'])->name('rekap.cuti');
});

Route::middleware('permission:rekapan-data')->group(function () {
    Route::get('/dashboard-data', [LaporanDashController::class, 'index'])->name('menu.pimpinan');
    //Route::get('/refresh-report', [LaporanDashController::class, 'reloadData'])->name('refresh.report');
    Route::get('/data-report/{id_data}', [LaporanDashController::class, 'detailLaporan'])->name('detail.report');
});
