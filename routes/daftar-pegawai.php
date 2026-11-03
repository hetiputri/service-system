<?php

use App\Http\Controllers\DaftarPegawai\DaftarPegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/kepegawaian/daftar-pegawai', [DaftarPegawaiController::class, 'daftarPegawaiView'])->name('pegawai.view');
// Route::get('/daftar-pegawai/pdf', [DaftarPegawaiController::class, 'exportPDF'])->name('daftar-pegawai.pdf');
// Route::get('/daftar-pegawai/excel', [DaftarPegawaiController::class, 'exportExcel'])->name('daftar-pegawai.excel');
Route::get('/kepegawaian/daftar-pegawai/csv', [DaftarPegawaiController::class, 'exportCsv'])->name('daftar-pegawai.csv');
Route::get('/kepegawaian/daftar-pegawai/data', [DaftarPegawaiController::class, 'daftarPegawaiData'])->name('daftar-pegawai.data');
