<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        // Menggunakan create agar ulasan baru masuk sebagai data baru (Riwayat)
        \App\Models\Review::create([
            'id_user' => \Illuminate\Support\Facades\Auth::id(),
            'id_kelas' => $id_kelas,
            'bintang' => $request->bintang,
            'isi_review' => $request->isi_review
        ]);

        return back()->with('success', 'Ulasan baru berhasil ditambahkan!');
    }
    // Menangani PUT /review/{id_review} (Update)
    public function update(Request $request, $id_review)
    {
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'isi_review' => 'required|string',
        ]);

        $review = Review::findOrFail($id_review);
        $review->update([
            'bintang' => $request->bintang,
            'isi_review' => $request->isi_review
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    // Menangani DELETE /review/{id_review} (Delete)
    public function destroy($id_review)
    {
        Review::findOrFail($id_review)->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
