<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Report;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik User
        $totalStudent = User::where('role', User::ROLE_USER)->count();
        $totalMentor = User::where('role', User::ROLE_MENTOR)->count();

        // 2. Statistik Kelas
        $totalKelas = Kelas::count();

        // 3. Total Pendapatan
        // Menggunakan asumsi status 'paid' seperti di web controller
        $totalPendapatan = Transaksi::where('status', 'paid')->sum('total_harga');

        // 4. Laporan Perlu Tindakan
        $laporanPending = Report::where('status', 'pending')->count();

        // 5. Transaksi Terbaru (5 data terakhir)
        $transaksiTerbaru = Transaksi::with('user')->latest('tgl_transaksi')->take(5)->get();

        return response()->json([
            'total_student' => $totalStudent,
            'total_mentor' => $totalMentor,
            'total_kelas' => $totalKelas,
            'total_pendapatan' => $totalPendapatan,
            'laporan_pending' => $laporanPending,
            'transaksi_terbaru' => $transaksiTerbaru
        ]);
    }
}
