<?php

use App\Http\Controllers\Api\Mentor\MateriController;
use App\Http\Controllers\Api\Mentor\MentorDashboardController;
use App\Http\Controllers\Api\Mentor\SubMateriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KeranjangController; // Pastikan path ini sesuai
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// detail kelas
Route::get('/kelas/{id_kelas}', [KelasController::class, 'show'])->name('Detail_kelas');
Route::post('/checkout', function () {
    return 'Logika Checkout akan dipasang disini. ID Kelas: '.request('id_kelas');
})->name('checkout.process');
Route::post('/cart/add/{id}', function ($id) {
    return 'Berhasil menambahkan Kelas ID: '.$id.' ke keranjang.';
})->name('cart.add');

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
    Route::middleware(['web'])->group(function () {
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{id}', [KeranjangController::class, 'addToCart'])->name('cart.add');
        Route::delete('/cart/remove/{id}', [KeranjangController::class, 'remove'])->name('cart.remove');
        Route::post('/checkout', function () {
            return 'Fitur Checkout akan dibuat di tahap selanjutnya.';
        })->name('checkout.process');
    });

    Route::middleware(['auth'])->group(function () {
        Route::post('/checkout', [TransaksiController::class, 'process'])->name('checkout.process');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('/transaksi/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');

        Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');

    });

});

// --- RUTE MENTOR ---
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
        Route::post('/', [MateriController::class, 'store'])->name('store');

        Route::get('/{id_materi}/edit', [MateriController::class, 'edit'])->name('edit');
        Route::put('/{id_materi}', [MateriController::class, 'update'])->name('update');
        Route::delete('/{id_materi}', [MateriController::class, 'destroy'])->name('destroy');

        // --- BAGIAN SUB-MATERI (Video/Dokumen) ---
        Route::prefix('{id_materi}/sub')->name('sub.')->group(function () {
            Route::get('/create', [SubMateriController::class, 'create'])->name('create');
            Route::post('/', [SubMateriController::class, 'store'])->name('store');
            Route::get('/{id_sub_materi}/edit', [SubMateriController::class, 'edit'])->name('edit');
            Route::put('/{id_sub_materi}', [SubMateriController::class, 'update'])->name('update');
            Route::delete('/{id_sub_materi}', [SubMateriController::class, 'destroy'])->name('destroy');
        });
    });
});
