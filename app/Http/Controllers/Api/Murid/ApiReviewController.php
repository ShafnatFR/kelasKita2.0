<?php

namespace App\Http\Controllers\Api\Murid;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiReviewController extends Controller
{
    public function index($id_kelas)
    {
        $reviews = Review::with('user')
            ->where('id_kelas', $id_kelas)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar ulasan berhasil diambil',
            'data' => $reviews
        ], 200);
    }

    public function store(Request $request, $id_kelas)
    {
        $validator = Validator::make($request->all(), [
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
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

    public function update(Request $request, $id_review)
    {
        $validator = Validator::make($request->all(), [
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // PERBAIKAN: Gunakan 'id_review' bukan 'id'
        $review = Review::where('id_review', $id_review)
            ->where('id_user', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan tidak ditemukan atau Anda tidak memiliki akses.'
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

    public function destroy($id_review)
    {
        // PERBAIKAN: Gunakan 'id_review' bukan 'id'
        $review = Review::where('id_review', $id_review)
            ->where('id_user', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus!'
        ], 200);
    }
}