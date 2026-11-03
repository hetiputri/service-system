<?php

use App\Http\Controllers\KonsepMutasi\KonsepMutasiController;
use Illuminate\Support\Facades\Route;

Route::get('/mutasi/konsep', [KonsepMutasiController::class, 'mutasiKonsepView'])
    ->middleware(['permission:r-mutasi-struktural'])
    ->name('konsep_mutasi.view');
Route::get('/mutasi/konsep/tambah', [KonsepMutasiController::class, 'tambahKonsepView'])
    ->middleware(['permission:c-mutasi-struktural'])
    ->name('tambah_konsep_mutasi.view');
Route::post('/mutasi/konsep', [KonsepMutasiController::class, 'create'])
    ->middleware(['permission:c-mutasi-struktural'])
    ->name('konsep_mutasi.create');
Route::get('/mutasi/konsep/{id_konsep}', [KonsepMutasiController::class, 'konsepDetailView'])
    ->middleware(['permission:r-mutasi-struktural-detail'])
    ->name('konsep_mutasi_detail.view');
Route::get('/mutasi/konsep/{id_konsep}/jabatan', [KonsepMutasiController::class, 'tambahJabatanView'])
    ->middleware(['permission:c-mutasi-struktural-detail', 'permission:c-mutasi-struktural-detail'])
    ->name('tambah_jabatan.view');
Route::get('/mutasi/konsep/{mutasiStruktural}/ubah', [KonsepMutasiController::class, 'ubahKonsepView'])
    ->middleware(['permission:u-mutasi-struktural'])
    ->name('ubah_jabatan.view');
Route::post('/mutasi/konsep/{mutasiStruktural}/jabatan', [KonsepMutasiController::class, 'tambahJabatan'])
    ->middleware('permission:c-mutasi-struktural-detail|u-mutasi-struktural-detail')
    ->name('tambah_jabatan.post');
Route::delete('/mutasi/konsep/{mutasiStruktural}/jabatan', [KonsepMutasiController::class, 'deleteDetail'])
    ->middleware(['permission:d-mutasi-struktural-detail'])
    ->name('hapus_jabatan.delete');
Route::put('/mutasi/konsep/{mutasiStruktural}', [KonsepMutasiController::class, 'update'])
    ->middleware(['permission:u-mutasi-struktural'])
    ->name('update_konsep.update');
Route::delete('/mutasi/konsep/{mutasiStruktural}', [KonsepMutasiController::class, 'delete'])
    ->middleware(['permission:d-mutasi-struktural'])
    ->name('hapus_konsep.delete');
