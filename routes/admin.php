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
    Route::get('/kelola-kelas', [KelasController::class, 'index'])->name('admin.kelola.kelas');
    Route::get('/kelola-report', [ReportController::class, 'index'])->name('admin.kelola.report');
    Route::get('/kelola-laporan', [LaporanController::class, 'index'])->name('admin.kelola.laporan');
    Route::get('/kelola-user', [UserController::class, 'index'])->name('admin.kelola.user');
});