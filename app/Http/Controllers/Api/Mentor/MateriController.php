<?php

namespace App\Http\Controllers\Api\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Kelas;

class MateriController extends Controller
{
    // === HALAMAN KURIKULUM (Lihat Bab & Sub-Bab) ===
    public function indexWeb($id_kelas)
    {
        $user = Auth::user();

        // 1. Validasi Kepemilikan Kelas
        $kelas = Kelas::where('id_kelas', $id_kelas)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();

        // 2. Ambil Materi (Bab) beserta Sub-Materinya
        // Kita urutkan berdasarkan 'urutan'
        $materiList = Materi::with('subMateri')
                            ->where('id_kelas', $id_kelas)
                            ->orderBy('urutan', 'asc')
                            ->get();

        return view('mentor.materi.index', compact('user', 'kelas', 'materiList'));
    }

    // === PROSES SIMPAN BAB BARU (MATERI) ===
    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
        ]);

        // Hitung urutan otomatis (ambil urutan terakhir + 1)
        $lastOrder = Materi::where('id_kelas', $id_kelas)->max('urutan') ?? 0;

        Materi::create([
            'id_kelas'     => $id_kelas,
            'judul_materi' => $request->judul_materi,
            'urutan'       => $lastOrder + 1,
        ]);

        return redirect()->back()->with('success', 'Bab berhasil ditambahkan!');
    }

    // ... function store dan destroy sudah ada ...

    // === FORM EDIT BAB ===
    public function edit($id_kelas, $id_materi)
    {
        $user = Auth::user();

        // 1. Validasi Kepemilikan Kelas
        $kelas = Kelas::where('id_kelas', $id_kelas)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();

        // 2. Ambil Data Bab yang mau diedit
        $materi = Materi::where('id_materi', $id_materi)
                        ->where('id_kelas', $id_kelas)
                        ->firstOrFail();

        return view('mentor.materi.edit', compact('user', 'kelas', 'materi'));
    }

    // === PROSES UPDATE BAB ===
    public function update(Request $request, $id_kelas, $id_materi)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'urutan'       => 'required|integer', // Sekalian update urutan kalau mau
        ]);

        // Cari Materi
        $materi = Materi::where('id_materi', $id_materi)
                        ->where('id_kelas', $id_kelas)
                        ->firstOrFail();

        // Update
        $materi->update([
            'judul_materi' => $request->judul_materi,
            'urutan'       => $request->urutan,
        ]);

        return redirect()->route('mentor.materi.index', $id_kelas)
                         ->with('success', 'Bab berhasil diperbarui!');
    }

    // === PROSES HAPUS BAB ===
    public function destroy($id_kelas, $id_materi)
    {
        $materi = Materi::where('id_materi', $id_materi)->firstOrFail();
        
        // Karena di migrasi Anda ada onCascadeDelete, 
        // Sub-Materi akan otomatis terhapus jika Bab dihapus.
        $materi->delete();

        return redirect()->back()->with('success', 'Bab berhasil dihapus.');
    }
}