<?php

use App\Http\Controllers\Bezetting\BezettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:read-bezetting')->group(function () {
    Route::get('/kepegawaian/bezetting', [BezettingController::class, 'bezettingView'])->name('bezetting.view');
    Route::get('/kepegawaian/bezetting/pdf', [BezettingController::class, 'exportPDF'])->name('bezetting.pdf');
    Route::get('/kepegawaian/bezetting/excel', [BezettingController::class, 'exportExcel'])->name('bezetting.excel');
    Route::get('/kepegawaian/bezetting/data', [BezettingController::class, 'getBezettingBySkpd'])->name('bezetting.data');
});
