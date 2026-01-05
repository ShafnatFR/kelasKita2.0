<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mentor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('=== REGISTER START ===');
        
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'username'   => 'required|string|unique:users,username|max:255',
                'email'      => 'required|email|unique:users,email|max:255',
                'password'   => 'required|string|min:6',
                'deskripsi'  => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Register validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'deskripsi'  => $request->deskripsi,
                'role'       => 'student',
            ]);

            Log::info('Register success', ['user_id' => $user->id_user]);
            Log::info('=== REGISTER END ===');

            return response()->json([
                'success' => true,
                'message' => 'Register berhasil',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::info('=== REGISTER END WITH ERROR ===');
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        Log::info('=== LOGIN START ===');
        
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                Log::warning('Login validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Finding user', ['email' => $request->email]);
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::warning('Login failed - invalid credentials', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            Log::info('Deleting old tokens', ['user_id' => $user->id_user]);
            $user->tokens()->delete();
            
            Log::info('Creating new token', ['user_id' => $user->id_user]);
            $token = $user->createToken('api-token')->plainTextToken;

            Log::info('Login success', ['user_id' => $user->id_user]);
            Log::info('=== LOGIN END ===');

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => [
                    'id_user' => $user->id_user,
                    'first_name' => $user->first_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::info('=== LOGIN END WITH ERROR ===');
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

            public function becomeMentor(Request $request)
{
    $token = $request->bearerToken();
    
    if (!$token) {
        return response()->json(['message' => 'Token tidak ditemukan'], 401);
    }

    $accessToken = PersonalAccessToken::findToken($token);
    if (!$accessToken || !$accessToken->tokenable) {
        return response()->json(['message' => 'Token tidak valid'], 401);
    }

    $user = $accessToken->tokenable;

    // Cek apakah sudah mentor
    if ($user->role === 'mentor') {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah menjadi mentor'
        ], 400);
    }

    try {
        // 1. Update role di users
        $user->role = 'mentor';
        $user->save();

        // 2. ✅ CREATE record di mentors (INI PENTING!)
        $mentor = Mentor::create([
            'id_user' => $user->id_user,
            'status' => 'pending', // Menunggu approval admin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendaftar sebagai mentor. Menunggu verifikasi admin.',
            'user' => $user,
            'mentor' => [
                'id_mentor' => $mentor->id_mentor,
                'status' => $mentor->status
            ]
        ], 200);

    } catch (\Exception $e) {
        Log::error('Become mentor error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mendaftar sebagai mentor',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function logout(Request $request)
    {
        Log::info('=== LOGOUT START ===');
        
        try {
            $request->user()->currentAccessToken()->delete();
            
            Log::info('Logout success');
            Log::info('=== LOGOUT END ===');

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::info('=== LOGOUT END WITH ERROR ===');
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}