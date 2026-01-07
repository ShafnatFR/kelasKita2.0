<?php
namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\JsonResponse;

class KelasApiController extends Controller
{
    public function show($id_kelas): JsonResponse
    {
        // Pakai id_kelas sesuai request
        $kelas = Kelas::with('mentor')->where('id_kelas', $id_kelas)->first();

        if (!$kelas) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
        }

        // Ambil materi sesuai id_kelas
        $materi = Materi::where('id_kelas', $id_kelas)->orderBy('urutan', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'kelas' => $kelas,
                'materi' => $materi
            ]
        ]);
    }
}