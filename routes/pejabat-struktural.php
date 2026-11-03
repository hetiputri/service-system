<?php

use App\Http\Controllers\PejabatStruktural\PejabatStrukturalController;
use Illuminate\Support\Facades\Route;

Route::get('/kepegawaian/pejabat-struktural', [PejabatStrukturalController::class, 'pejabatStrukturalView'])->name('pejabat_struktural.view');
Route::get('/kepegawaian/pejabat-struktural/csv', [PejabatStrukturalController::class, 'exportCsv'])->name('pejabat_struktural.csv');
Route::get('/kepegawaian/pejabat/data', [PejabatStrukturalController::class, 'pejabatStrukturalData'])->name('pejabat.data');
