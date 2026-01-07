<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::query();

        // 1. Fitur Search (Cari Text)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', '%'.$search.'%')
                    ->orWhere('kategori', 'like', '%'.$search.'%')
                    ->orWhere('status_publikasi', 'like', '%'.$search.'%')
                    ->orWhereHas('mentor.user', function ($qUser) use ($search) {
                        $qUser->where('username', 'like', '%'.$search.'%');
                    });
            });
        }

        // 2. Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_publikasi', $request->status);
        }

        $kelas = $query->with('mentor.user')->latest()->paginate(10);

        return response()->json($kelas);
    }

    public function show($id)
    {
        $kelas = Kelas::with(['mentor.user', 'materi.subMateri', 'adminNote'])->findOrFail($id);

        return response()->json($kelas);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_publikasi' => 'required|in:draft,published,archived',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update(['status_publikasi' => $request->status_publikasi]);

        return response()->json([
            'message' => 'Status kelas berhasil diperbarui.',
            'data' => $kelas
        ]);
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string',
        ]);

        $kelas = Kelas::findOrFail($id);

        if ($kelas->adminNote) {
            $kelas->adminNote->update(['content' => $request->catatan_admin]);
        } else {
            $kelas->adminNote()->create(['content' => $request->catatan_admin]);
        }

        return response()->json([
            'message' => 'Catatan admin berhasil disimpan.'
        ]);
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Cek apakah kelas sudah pernah dibeli (ada di tabel transaksi_detail)
        if ($kelas->transaksiDetails()->exists()) {
            return response()->json([
                'message' => 'Kelas tidak dapat dihapus karena memiliki riwayat transaksi. Silakan ubah status menjadi Archive.'
            ], 400);
        }

        $kelas->delete();

        return response()->json([
            'message' => 'Kelas berhasil dihapus.'
        ]);
    }
}
