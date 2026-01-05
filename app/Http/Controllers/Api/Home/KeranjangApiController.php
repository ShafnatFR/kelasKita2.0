<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KeranjangApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->id_user; // Dikirim dari Web Controller
        // Relasi ke 'kelas' wajib ada di Model Keranjang
        $keranjang = Keranjang::with('kelas')->where('id_user', $userId)->get();
        return response()->json(['success' => true, 'data' => $keranjang]);
    }

    public function store(Request $request): JsonResponse
    {
        $userId = $request->id_user;
        $idKelas = $request->id_kelas;

        $exists = Keranjang::where('id_user', $userId)->where('id_kelas', $idKelas)->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Sudah ada'], 400);
        }

        Keranjang::create([
            'id_user' => $userId, 
            'id_kelas' => $idKelas
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id_keranjang): JsonResponse
    {
        // Hapus berdasarkan id_keranjang (PK)
        Keranjang::where('id_keranjang', $id_keranjang)->delete();
        return response()->json(['success' => true]);
    }
}