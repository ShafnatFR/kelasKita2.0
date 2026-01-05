<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubMateri;
use App\Models\Materi;
use App\Models\Kelas;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SubMateriController extends Controller
{
    // Helper: Validasi kepemilikan via Materi -> Kelas -> Mentor
    private function isMateriOwner($user, $id_materi)
    {
        $materi = Materi::find($id_materi);
        if (!$materi) return false;

        $mentor = Mentor::where('id_user', $user->id_user)->first();
        if (!$mentor) return false;

        // Cek apakah kelas dari materi ini milik mentor
        return Kelas::where('id_kelas', $materi->id_kelas)
            ->where('id_mentor', $mentor->id_mentor)
            ->exists();
    }

    /**
     * GET /api/sub-materi?id_materi=1
     */
    public function index(Request $request)
    {
        if (!$request->has('id_materi')) {
            return response()->json(['message' => 'Parameter id_materi wajib ada'], 400);
        }

        $subMateri = SubMateri::where('id_materi', $request->id_materi)
            // ->with(['video', 'dokumen']) // Uncomment jika Model Video/Dokumen sudah ada
            ->orderBy('urutan', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $subMateri], 200);
    }

    /**
     * POST /api/sub-materi
     * Isi konten materi (Teks, atau ID Video/Dokumen)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_materi' => 'required|exists:materi,id_materi',
                'judul_sub' => 'required|string|max:255',
                'urutan' => 'required|integer',
                'teks_pembelajaran' => 'nullable|string',
                // Pastikan tabel videos dan dokumens sudah ada jika validasi ini diaktifkan:
                'id_video' => 'nullable|integer', // |exists:videos,id_video
                'id_dokumen' => 'nullable|integer', // |exists:dokumens,id_dokumen
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Security Check
            if (!$this->isMateriOwner($request->user(), $request->id_materi)) {
                return response()->json(['success' => false, 'message' => 'Forbidden: Materi bukan milik Anda'], 403);
            }

            $subMateri = SubMateri::create([
                'id_materi' => $request->id_materi,
                'judul_sub' => $request->judul_sub,
                'urutan' => $request->urutan,
                'teks_pembelajaran' => $request->teks_pembelajaran,
                'id_video' => $request->id_video,
                'id_dokumen' => $request->id_dokumen,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub materi berhasil dibuat',
                'data' => $subMateri
            ], 201);

        } catch (\Exception $e) {
            Log::error('Create sub-materi error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * PUT /api/sub-materi/{id_sub_materi}
     */
    public function update(Request $request, $id_sub_materi)
    {
        try {
            $subMateri = SubMateri::find($id_sub_materi);
            if (!$subMateri) return response()->json(['message' => 'Sub Materi tidak ditemukan'], 404);

            // Security Check
            if (!$this->isMateriOwner($request->user(), $subMateri->id_materi)) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            // Update data yang dikirim saja
            $subMateri->update($request->only([
                'judul_sub', 'urutan', 'teks_pembelajaran', 'id_video', 'id_dokumen'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Sub materi berhasil diupdate',
                'data' => $subMateri
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * DELETE /api/sub-materi/{id_sub_materi}
     */
    public function destroy(Request $request, $id_sub_materi)
    {
        try {
            $subMateri = SubMateri::find($id_sub_materi);
            if (!$subMateri) return response()->json(['message' => 'Sub Materi tidak ditemukan'], 404);

            if (!$this->isMateriOwner($request->user(), $subMateri->id_materi)) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $subMateri->delete();

            return response()->json(['success' => true, 'message' => 'Sub materi berhasil dihapus'], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }
}