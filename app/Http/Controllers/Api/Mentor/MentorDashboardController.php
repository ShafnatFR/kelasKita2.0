<?php

namespace App\Http\Controllers\Api\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\User;

class MentorDashboardController extends Controller
{
    public function indexWeb()
    {
        $user = Auth::user();

        // Cek apakah user ini benar-benar mentor
        if ($user->role !== 'mentor') {
            abort(403, 'Akses Ditolak');
        }

        // --- HITUNG STATISTIK ---
        
        // 1. Total Kelas (Sesuai ID User yang login)
        $totalKelas = Kelas::where('id_mentor', $user->id_user)->count();

        // 2. Total Siswa & Pendapatan (Sementara kita set 0 dulu jika belum ada tabel transaksi)
        // Nanti jika modul transaksi sudah ada, kita update query ini.
        $totalSiswa = 0; 
        $totalPendapatan = 0; 
        $avgRating = 0;

        // Contoh Query jika nanti tabel transaksi sudah ada:
        /*
        $kelasIds = Kelas::where('id_mentor', $user->id_user)->pluck('id_kelas');
        $totalSiswa = TransaksiDetail::whereIn('id_kelas', $kelasIds)->count();
        $totalPendapatan = TransaksiDetail::whereIn('id_kelas', $kelasIds)->sum('harga_saat_beli');
        */

        // Kirim data ke View
        return view('mentor.dashboard', compact(
            'user', 
            'totalKelas', 
            'totalSiswa', 
            'totalPendapatan', 
            'avgRating'
        ));
    }
}