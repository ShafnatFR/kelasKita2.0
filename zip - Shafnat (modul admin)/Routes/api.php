<?php

use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| API Routes - Admin Module Only                                            
|-------------------------------------------------------------------------- 
*/

Route::prefix('admin')->group(function () {
    // Public routes (Admin Login)
    Route::post('/login', [\App\Http\Controllers\Api\Admin\AuthController::class, 'login']);

    // Protected routes
    Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Admin\AuthController::class, 'logout']);
        Route::get('/me', [\App\Http\Controllers\Api\Admin\AuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
            Route::patch('/{id}/status', [\App\Http\Controllers\Api\Admin\UserController::class, 'updateStatus']);
            Route::patch('/{id}/catatan', [\App\Http\Controllers\Api\Admin\UserController::class, 'updateCatatan']);
            Route::patch('/{id}/activate', [\App\Http\Controllers\Api\Admin\UserController::class, 'activate']);
        });

        // Kelas Management
        Route::prefix('kelas')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\KelasController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\Admin\KelasController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\KelasController::class, 'destroy']);
            Route::patch('/{id}/status', [\App\Http\Controllers\Api\Admin\KelasController::class, 'updateStatus']);
            Route::patch('/{id}/catatan', [\App\Http\Controllers\Api\Admin\KelasController::class, 'updateCatatan']);
        });

        // Laporan (Transaksi) Management
        Route::prefix('laporan')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\LaporanController::class, 'index']);
        });

        // Report (User Complaints) Management
        Route::prefix('reports')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\ReportController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\Admin\ReportController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\ReportController::class, 'destroy']);
            Route::patch('/{id}/status', [\App\Http\Controllers\Api\Admin\ReportController::class, 'updateStatus']);
            Route::patch('/{id}/catatan', [\App\Http\Controllers\Api\Admin\ReportController::class, 'updateCatatan']);
        });
    });
});
