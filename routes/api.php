<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| User Global Info
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin')->group(function () {
    // Public routes
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
    });
});

