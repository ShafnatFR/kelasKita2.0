<?php

namespace App\Http\Controllers\Api\Murid;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiReviewController extends Controller
{
    /**
     * Menampilkan daftar ulasan berdasarkan ID Kelas.
     */
    public function index($id_kelas)
    {
        // Mengambil review beserta data user (nama & foto)
        $reviews = Review::with('user:id,name,avatar')
            ->where('id_kelas', $id_kelas)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar ulasan berhasil diambil',
            'data' => $reviews
        ], 200);
    }

    /**
     * Menyimpan ulasan baru untuk kelas tertentu.
     */
    public function store(Request $request, $id_kelas)
    {
        $validator = Validator::make($request->all(), [
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $review = Review::create([
            'id_user' => Auth::id(),
            'id_kelas' => $id_kelas,
            'bintang' => $request->bintang,
            'isi_review' => $request->isi_review
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan baru berhasil ditambahkan!',
            'data' => $review
        ], 201);
    }

    /**
     * Memperbarui ulasan yang sudah ada.
     */
    public function update(Request $request, $id_review)
    {
        $validator = Validator::make($request->all(), [
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mencari berdasarkan kolom 'id_review' dan memastikan milik user yang login
        $review = Review::where('id_review', $id_review)
            ->where('id_user', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan tidak ditemukan atau Anda tidak memiliki akses untuk mengubahnya.'
            ], 404);
        }

        $review->update([
            'bintang' => $request->bintang,
            'isi_review' => $request->isi_review
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil diperbarui!',
            'data' => $review
        ], 200);
    }

    /**
     * Menghapus ulasan.
     */
    public function destroy($id_review)
    {
        // Mencari berdasarkan kolom 'id_review' dan memastikan milik user yang login
        $review = Review::where('id_review', $id_review)
            ->where('id_user', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan tidak ditemukan atau Anda tidak memiliki akses untuk menghapusnya.'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus!'
        ], 200);
    }
}