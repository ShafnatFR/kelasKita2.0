<?php
namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KeranjangApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Relasi ke 'kelas' wajib ada di Model Keranjang
            $keranjang = Keranjang::with('kelas')
                ->where('id_user', $user->id_user)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $keranjang
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'id_kelas' => 'required|exists:kelas,id_kelas'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $exists = Keranjang::where('id_user', $user->id_user)
                ->where('id_kelas', $request->id_kelas)
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas sudah ada di keranjang'
                ], 400);
            }

            $keranjang = Keranjang::create([
                'id_user' => $user->id_user, 
                'id_kelas' => $request->id_kelas
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan ke keranjang',
                'data' => $keranjang
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id_keranjang): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $keranjang = Keranjang::where('id_keranjang', $id_keranjang)
                ->where('id_user', $user->id_user)
                ->first();

            if (!$keranjang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item keranjang tidak ditemukan'
                ], 404);
            }

            $keranjang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}