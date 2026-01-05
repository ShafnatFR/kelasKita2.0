<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Mentor\MentorDashboardController; // Pastikan path ini sesuai
use App\Http\Controllers\HomeController;    
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Api\Mentor\KelasController;
use App\Http\Controllers\Api\Mentor\MateriController;
use App\Http\Controllers\Api\Mentor\SubMateriController;

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

//detail kelas
Route::get('/kelas/{id_kelas}', [StudentKelasController::class, 'show'])->name('kelas.detail');

// Route untuk menampilkan view ini
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Halaman Register & Prosesnya
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

 Route::middleware(['auth'])->group(function () {

    // 1. Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::delete('/keranjang/hapus/{id_keranjang}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // 2. Transaksi & Checkout
    Route::post('/checkout', [TransaksiController::class, 'checkout'])->name('checkout.process');
    Route::get('/transaksi/{id_transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');

    Route::get('/student/dashboard', function () {
        return "Halo Siswa! Ini Dashboard Student (Masih Kosong).";
    })->name('student.dashboard');

});

Route::middleware(['auth'])->prefix('mentor')->name('mentor.')->group(function () {

    // 1. DASHBOARD
    Route::get('/dashboard', [MentorDashboardController::class, 'indexWeb'])->name('dashboard');

    // 2. MANAJEMEN KELAS (CRUD LENGKAP)
    Route::get('/kelas', [KelasController::class, 'indexWeb'])->name('kelas.index'); // List
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create'); // Form
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store'); // Simpan
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit'); // Edit
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update'); // Update
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy'); // Hapus

    // 3. MANAJEMEN MATERI (BAB) & SUB-MATERI
    // URL: mentor/kelas/{id_kelas}/materi/...
    Route::prefix('kelas/{id_kelas}/materi')->name('materi.')->group(function () {
        
        // --- BAGIAN BAB (Materi) ---
        Route::get('/', [MateriController::class, 'indexWeb'])->name('index'); // Halaman Kurikulum
        Route::post('/', [MateriController::class, 'store'])->name('store'); // <--- INI YG SEBELUMNYA HILANG
        
        Route::get('/{id_materi}/edit', [MateriController::class, 'edit'])->name('edit');
        Route::put('/{id_materi}', [MateriController::class, 'update'])->name('update');
        Route::delete('/{id_materi}', [MateriController::class, 'destroy'])->name('destroy');

        // --- BAGIAN SUB-MATERI (Video/Dokumen) ---
        // Nanti kita pakai ini untuk upload file
        Route::prefix('{id_materi}/sub')->name('sub.')->group(function () {
             Route::get('/create', [SubMateriController::class, 'create'])->name('create');
             Route::post('/', [SubMateriController::class, 'store'])->name('store');
                Route::get('/{id_sub_materi}/edit', [SubMateriController::class, 'edit'])->name('edit');
                Route::put('/{id_sub_materi}', [SubMateriController::class, 'update'])->name('update');
                Route::delete('/{id_sub_materi}', [SubMateriController::class, 'destroy'])->name('destroy');
        });
    });

});
