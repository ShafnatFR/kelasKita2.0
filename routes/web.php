<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->group(function () {
    // 1. FITUR PROGRESS (Halaman Belajar & Checklist)
    Route::get('/kelas/{id_kelas}/belajar', [ProgressController::class, 'show'])->name('kelas.belajar');
    Route::post('/progress/toggle', [ProgressController::class, 'toggle'])->name('progress.toggle');
    Route::get('/learning', [ProgressController::class, 'index'])->name('learning.index');
    // 2. FITUR REVIEW (CRUD: Create, Update, Delete)
    Route::post('/review/{id_kelas}', [ReviewController::class, 'store'])->name('review.store');
    Route::put('/review/{id_review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id_review}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // 3. FITUR REPORT (Pelaporan Masalah)
    Route::post('/report/store/{id_kelas}', [App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
});
