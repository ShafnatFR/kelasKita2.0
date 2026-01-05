<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Kelas;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MateriController extends Controller
{
    // Helper: Cek apakah Kelas milik Mentor yang sedang login
    private function isKelasOwner($user, $id_kelas)
    {
        $mentor = Mentor::where('id_user', $user->id_user)->first();
        if (!$mentor) return false;

        return Kelas::where('id_kelas', $id_kelas)
            ->where('id_mentor', $mentor->id_mentor)
            ->exists();
    }

    /**
     * GET /api/materi?id_kelas=1
     * Mengambil daftar materi (Bab) beserta sub-materinya
     */
    public function index(Request $request)
    {
        try {
            if (!$request->has('id_kelas')) {
                return response()->json(['success' => false, 'message' => 'Parameter id_kelas wajib ada'], 400);
            }

            // Opsional: Cek akses (apakah mentor pemilik / student yang beli)
            // Disini kita loloskan saja untuk read, logic pembatasan bisa ditambah nanti

            $materi = Materi::where('id_kelas', $request->id_kelas)
                ->with(['subMateri' => function ($query) {
                    $query->orderBy('urutan', 'asc'); // Urutkan sub materi
                }])
                ->orderBy('urutan', 'asc') // Urutkan materi induk
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'List materi berhasil diambil',
                'data' => $materi
            ], 200);

        } catch (\Exception $e) {
            Log::error('Get materi error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * POST /api/materi
     * Membuat Bab Baru
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_kelas' => 'required|exists:kelas,id_kelas',
                'judul_materi' => 'required|string|max:255',
                'urutan' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Security Check
            if (!$this->isKelasOwner($request->user(), $request->id_kelas)) {
                return response()->json(['success' => false, 'message' => 'Forbidden: Kelas bukan milik Anda'], 403);
            }

            $materi = Materi::create([
                'id_kelas' => $request->id_kelas,
                'judul_materi' => $request->judul_materi,
                'urutan' => $request->urutan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bab materi berhasil dibuat',
                'data' => $materi
            ], 201);

        } catch (\Exception $e) {
            Log::error('Create materi error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * PUT /api/materi/{id_materi}
     * Update Bab (Judul/Urutan)
     */
    public function update(Request $request, $id_materi)
    {
        try {
            $materi = Materi::find($id_materi);
            if (!$materi) return response()->json(['message' => 'Materi tidak ditemukan'], 404);

            if (!$this->isKelasOwner($request->user(), $materi->id_kelas)) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $materi->update($request->only(['judul_materi', 'urutan']));

            return response()->json([
                'success' => true,
                'message' => 'Bab materi berhasil diupdate',
                'data' => $materi
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * DELETE /api/materi/{id_materi}
     * Hapus Bab (Sub materi akan terhapus otomatis karena cascade di DB)
     */
    public function destroy(Request $request, $id_materi)
    {
        try {
            $materi = Materi::find($id_materi);
            if (!$materi) return response()->json(['message' => 'Materi tidak ditemukan'], 404);

            if (!$this->isKelasOwner($request->user(), $materi->id_kelas)) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $materi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bab materi berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }
}