<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Rute Login (Bisa diakses tanpa login)
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.auth.submit');
});

// Grup Rute yang Dilindungi (Wajib Login)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Menu Management
    Route::get('/kelola-user', [UserController::class, 'index'])->name('admin.kelola.user');
    // Kelola Kelas
    Route::get('/kelola-kelas', [KelasController::class, 'index'])->name('admin.kelola.kelas');
    Route::get('/kelola-kelas/{id}', [KelasController::class, 'show'])->name('admin.kelola.kelas.show');
    Route::put('/kelola-kelas/{id}/status', [KelasController::class, 'updateStatus'])->name('admin.kelola.kelas.update-status');
    Route::put('/kelola-kelas/{id}/catatan', [KelasController::class, 'updateCatatan'])->name('admin.kelola.kelas.update-catatan');
    Route::delete('/kelola-kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelola.kelas.destroy');

    Route::get('/kelola-report', [ReportController::class, 'index'])->name('admin.kelola.report');
    Route::get('/kelola-laporan', [LaporanController::class, 'index'])->name('admin.kelola.laporan');
    Route::get('/kelola-user', [UserController::class, 'index'])->name('admin.kelola.user');
});