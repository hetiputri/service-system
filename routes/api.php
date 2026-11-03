<?php

use App\Http\Controllers\Api\DataCpnsController;
use App\Http\Controllers\Api\DataCuti;
use App\Http\Controllers\Api\DataGajiBerkala;
use App\Http\Controllers\Api\DataHukdis;
use App\Http\Controllers\Api\DataJabatanController;
use App\Http\Controllers\Api\DataKeluargaController;
use App\Http\Controllers\Api\DataPangkatController;
use App\Http\Controllers\Api\DataPendidikanController;
use App\Http\Controllers\Api\DataPenghargaanController;
use App\Http\Controllers\Api\DataPi;
use App\Http\Controllers\Api\DataPmk;
use App\Http\Controllers\Api\DataPnsController;
use App\Http\Controllers\Api\DataPribadiController;
use App\Http\Controllers\Api\DiklatController;
use App\Http\Controllers\Api\PencarianController;
use App\Http\Controllers\Api\Pensiun;
use App\Http\Controllers\Api\Referensi;
use App\Http\Controllers\Api\TestingApi;
use App\Http\Controllers\Otentikasi\LoginController;
// use App\Http\Controllers\RolePermission\PermissionLists;
use App\Http\Controllers\RolePermission\RoleLists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth-simpel', [LoginController::class, 'login']);

// Route::get('/menu', [LoginController::class, 'menu']); //->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/pegawai-profile/{id_pegawai}', [DataPribadiController::class, 'DataPribadi']);
    Route::get('/data-cpns/{id_pegawai}', [DataCpnsController::class, 'Datacpns']);
    Route::get('/data-pns/{id_pegawai}', [DataPnsController::class, 'Datapns']);
    Route::get('/data-keluarga/{id_pegawai}', [DataKeluargaController::class, 'Datakeluarga']);
    Route::get('/data-riwayat/pangkat/{id_pegawai}', [DataPangkatController::class, 'DataPangkat']);
    Route::get('/data-riwayat/jabatan/{id_pegawai}', [DataJabatanController::class, 'DataJabatan']);
    Route::get('/data-riwayat/pendidikan/{id_pegawai}', [DataPendidikanController::class, 'DataPendidikan']);
    Route::get('/data-riwayat/diklat-struktural/{id_pegawai}', [DiklatController::class, 'DiklatStruktural']);
    Route::get('/data-riwayat/diklat-lainnya/{id_pegawai}', [DiklatController::class, 'DiklatLainnya']);
    Route::get('/data-riwayat/penghargaan/{id_pegawai}', [DataPenghargaanController::class, 'Riwayatpenghargaan']);
    Route::get('/data-riwayat/gaji-berkala/{id_pegawai}', [DataGajiBerkala::class, 'Riwayatgajiberkala']);
    Route::get('/data-riwayat/hukuman-disiplin/{id_pegawai}', [DataHukdis::class, 'Riwayathukdis']);
    Route::get('/data-riwayat/cuti/{id_pegawai}', [DataCuti::class, 'Riwayatcuti']);
    Route::get('/data-riwayat/pmk/{id_pegawai}', [DataPmk::class, 'Riwayatpmk']);
    Route::get('/data-riwayat/pi/{id_pegawai}', [DataPi::class, 'Riwayatpi']);

    Route::get('/referensi/{jenis}', [Referensi::class, 'ReferensiData']);
    Route::post('/filter-data', [PencarianController::class, 'filter']);

    // Route::get('/permission/list', [PermissionLists::class, 'render']);
    // Route::post('/permission/create', [PermissionLists::class, 'create']);
    // Route::post('/permission/update', [PermissionLists::class, 'update']);

    Route::get('/role/list', [RoleLists::class, 'render']);
    Route::post('/role/create', [RoleLists::class, 'create']);
    Route::post('/role/update', [RoleLists::class, 'update']);
    Route::get('/role/permission-list/{role_name}', [RoleLists::class, 'CekPermissionOnRole']);
    Route::post('/role/permission-add', [RoleLists::class, 'AssignPermissionOnRole']);

    // Route::get('/pengguna/list', [RoleLists::class, 'Penggunarender']);
    // Route::post('/pengguna/update-role', [RoleLists::class, 'PenggunaRoleUpdate']);
    // Route::get('/pengguna/role-list/{id_user}', [RoleLists::class, 'CekRoleUser']);

    Route::get('/pensiun/pengajuan/{id_pegawai}', [Pensiun::class, 'AjuanPensiun']);
    Route::get('/pengguna/test-role', [RoleLists::class, 'TestRole']); // ->middleware(['role:admin-master']);

});

Route::get('/jabatan/testing', [TestingApi::class, 'getWithFiltersJabatan']);
Route::get('/pegawai/jabatan', [TestingApi::class, 'pegawaiDenganJabatanTerbaru']);
