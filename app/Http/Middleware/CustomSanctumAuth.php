<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;

class CustomSanctumAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('=== CUSTOM AUTH MIDDLEWARE START ===');
        
        // 1. Ambil token dari header
        $token = $request->bearerToken();
        
        if (!$token) {
            Log::warning('Token tidak ditemukan di header');
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 401);
        }

        // 2. Cari token di database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            Log::warning('Token tidak valid atau sudah dihapus');
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }

        // 3. Ambil User pemilik token
        $user = $accessToken->tokenable;

        if (!$user) {
            Log::warning('User tidak ditemukan dari token');
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        // 4. Set user ke request agar bisa diakses via $request->user()
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        Log::info('Custom auth success', ['user_id' => $user->id_user]);
        Log::info('=== CUSTOM AUTH MIDDLEWARE END ===');

        return $next($request);
    }
}