<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Penting untuk upload file

class KelasController extends Controller
{
    /**
     * GET /api/kelas
     * List semua kelas milik mentor yang login
     */
    public function index(Request $request)
    {
        Log::info('=== GET KELAS LIST START ===');
        
        try {
            $user = $request->user();

            // 1. Cek Role
            if ($user->role !== 'mentor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya mentor yang bisa mengakses endpoint ini'
                ], 403);
            }

            // 2. Ambil data Mentor
            $mentor = Mentor::where('id_user', $user->id_user)->first();

            if (!$mentor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mentor tidak ditemukan. Silakan daftar sebagai mentor terlebih dahulu.'
                ], 404);
            }

            // 3. Ambil kelas sesuai id_mentor
            $kelas = Kelas::where('id_mentor', $mentor->id_mentor)
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Kelas list retrieved', ['count' => $kelas->count()]);

            // Opsional: Append full URL thumbnail agar frontend mudah akses
            $kelas->transform(function ($item) {
                if ($item->thumbnail) {
                    $item->thumbnail_url = url('storage/' . $item->thumbnail);
                }
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diambil',
                'data' => $kelas
            ], 200);

        } catch (\Exception $e) {
            Log::error('Get kelas list error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/kelas
     * Create kelas baru dengan upload gambar
     */
    public function store(Request $request)
    {
        Log::info('=== CREATE KELAS START ===');
        
        try {
            $user = $request->user();

            if ($user->role !== 'mentor') {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $mentor = Mentor::where('id_user', $user->id_user)->first();
            if (!$mentor) {
                return response()->json(['success' => false, 'message' => 'Mentor not found'], 404);
            }

            // 1. Validasi
            $validator = Validator::make($request->all(), [
                'nama_kelas' => 'required|string|max:255',
                'description' => 'required|string',
                'harga' => 'required|numeric|min:0',
                'kategori' => 'required|string|max:100',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 2. Handle Upload Gambar
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                // Nama file unik: time_namafileasli
                $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                // Simpan ke storage/app/public/thumbnails
                $path = $file->storeAs('public/thumbnails', $filename);
                // Simpan path relatif untuk database: thumbnails/namafile.jpg
                $thumbnailPath = 'thumbnails/' . $filename;
            }

            // 3. Simpan ke Database
            $kelas = Kelas::create([
                'id_mentor' => $mentor->id_mentor,
                'nama_kelas' => $request->nama_kelas,
                'description' => $request->description,
                'harga' => $request->harga,
                'kategori' => $request->kategori,
                'thumbnail' => $thumbnailPath,
                'status_publikasi' => 'draft',
            ]);

            Log::info('Kelas created', ['id_kelas' => $kelas->id_kelas]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dibuat',
                'data' => $kelas
            ], 201);

        } catch (\Exception $e) {
            Log::error('Create kelas error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/kelas/{id_kelas}
     */
    public function show(Request $request, $id_kelas)
    {
        try {
            $user = $request->user();

            if ($user->role !== 'mentor') {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $mentor = Mentor::where('id_user', $user->id_user)->first();
            if (!$mentor) return response()->json(['message' => 'Mentor not found'], 404);

            $kelas = Kelas::where('id_kelas', $id_kelas)
                ->where('id_mentor', $mentor->id_mentor)
                ->first();

            if (!$kelas) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
            }

            // Append URL Thumbnail
            if ($kelas->thumbnail) {
                $kelas->thumbnail_url = url('storage/' . $kelas->thumbnail);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail kelas berhasil diambil',
                'data' => $kelas
            ], 200);

        } catch (\Exception $e) {
            Log::error('Show kelas error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * PUT /api/kelas/{id_kelas}
     * Update kelas termasuk ganti gambar
     */
    public function update(Request $request, $id_kelas)
    {
        Log::info('=== UPDATE KELAS START ===');
        try {
            $user = $request->user();

            if ($user->role !== 'mentor') {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $mentor = Mentor::where('id_user', $user->id_user)->first();
            if (!$mentor) return response()->json(['message' => 'Mentor not found'], 404);

            // Cari kelas
            $kelas = Kelas::where('id_kelas', $id_kelas)
                ->where('id_mentor', $mentor->id_mentor)
                ->first();

            if (!$kelas) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
            }

            // 1. Validasi
            $validator = Validator::make($request->all(), [
                'nama_kelas' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'harga' => 'sometimes|required|numeric|min:0',
                'kategori' => 'sometimes|required|string|max:100',
                'status_publikasi' => 'sometimes|required|in:draft,published,archived',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $dataToUpdate = $request->only([
                'nama_kelas', 'description', 'harga', 'kategori', 'status_publikasi'
            ]);

            // 2. Handle Ganti Gambar
            if ($request->hasFile('thumbnail')) {
                // Hapus gambar lama jika ada
                if ($kelas->thumbnail && Storage::exists('public/' . $kelas->thumbnail)) {
                    Storage::delete('public/' . $kelas->thumbnail);
                }

                // Upload gambar baru
                $file = $request->file('thumbnail');
                $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $path = $file->storeAs('public/thumbnails', $filename);
                
                // Masukkan ke array update
                $dataToUpdate['thumbnail'] = 'thumbnails/' . $filename;
            }

            // 3. Update Database
            $kelas->update($dataToUpdate);

            Log::info('Kelas updated', ['id_kelas' => $kelas->id_kelas]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diupdate',
                'data' => $kelas
            ], 200);

        } catch (\Exception $e) {
            Log::error('Update kelas error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/kelas/{id_kelas}
     */
    public function destroy(Request $request, $id_kelas)
    {
        try {
            $user = $request->user();

            if ($user->role !== 'mentor') {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $mentor = Mentor::where('id_user', $user->id_user)->first();
            if (!$mentor) return response()->json(['message' => 'Mentor not found'], 404);

            $kelas = Kelas::where('id_kelas', $id_kelas)
                ->where('id_mentor', $mentor->id_mentor)
                ->first();

            if (!$kelas) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
            }

            // 1. Hapus File Gambar dari Storage (Bersih-bersih)
            if ($kelas->thumbnail && Storage::exists('public/' . $kelas->thumbnail)) {
                Storage::delete('public/' . $kelas->thumbnail);
            }

            // 2. Hapus Data dari Database
            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Delete kelas error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }
}