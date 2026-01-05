<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\Kelas;
use App\Models\TransaksiDetail;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MentorDashboardController extends Controller
{
    // Helper: Ambil data Mentor
    private function getMentor($user) {
        return Mentor::where('id_user', $user->id_user)->first();
    }

    /**
     * 1. DASHBOARD UTAMA (SUMMARY)
     * Ini untuk "View" Halaman Home Dashboard Mentor
     */
    public function getDashboardSummary(Request $request)
    {
        $user = $request->user();
        $mentor = $this->getMentor($user);

        if (!$mentor) return response()->json(['success' => false, 'message' => 'Bukan Mentor'], 403);

        $kelasIds = Kelas::where('id_mentor', $mentor->id_mentor)->pluck('id_kelas');

        // A. Total Kelas Aktif
        $totalKelas = Kelas::where('id_mentor', $mentor->id_mentor)->count();

        // B. Total Siswa (Unik)
        // Gabungkan transaksi_detail ke transaksi induk untuk cek status 'paid'
        $totalSiswa = TransaksiDetail::whereIn('id_kelas', $kelasIds)
            ->whereHas('transaksi', function($q) {
                $q->where('status', 'paid');
            })
            ->join('transaksi', 'transaksi.id_transaksi', '=', 'transaksi_detail.id_transaksi')
            ->distinct('transaksi.id_user') // Hitung orangnya, bukan jumlah belinya
            ->count('transaksi.id_user');

        // C. Total Pendapatan
        $totalPendapatan = TransaksiDetail::whereIn('id_kelas', $kelasIds)
            ->whereHas('transaksi', function($q) {
                $q->where('status', 'paid');
            })->sum('harga_saat_beli');

        // D. Rata-rata Rating
        $avgRating = Review::whereIn('id_kelas', $kelasIds)->avg('bintang');

        // E. 5 Transaksi Terakhir (Activity Feed)
        $recentActivities = TransaksiDetail::whereIn('id_kelas', $kelasIds)
            ->whereHas('transaksi', function($q) {
                $q->where('status', 'paid');
            })
            ->with([
                'transaksi.user:id_user,fullname,foto_profil', // Data Siswa (Sesuaikan kolom user Anda)
                'kelas:id_kelas,nama_kelas,thumbnail'         // Data Kelas
            ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard summary loaded',
            'data' => [
                'cards' => [
                    'total_kelas' => $totalKelas,
                    'total_siswa' => $totalSiswa,
                    'total_pendapatan' => (int) $totalPendapatan,
                    'rating_average' => round($avgRating, 1) ?? 0,
                ],
                'recent_activities' => $recentActivities
            ]
        ]);
    }

    /**
     * 2. DETAIL PENDAPATAN
     * Untuk Halaman "Laporan Keuangan"
     */
    public function getPendapatan(Request $request)
    {
        $user = $request->user();
        $mentor = $this->getMentor($user);
        if (!$mentor) return response()->json(['success' => false], 403);

        $kelasIds = Kelas::where('id_mentor', $mentor->id_mentor)->pluck('id_kelas');

        // Total Keseluruhan
        $totalPendapatan = TransaksiDetail::whereIn('id_kelas', $kelasIds)
            ->whereHas('transaksi', function($q) { $q->where('status', 'paid'); })
            ->sum('harga_saat_beli');

        // Rincian Per Kelas
        $rincian = Kelas::where('id_mentor', $mentor->id_mentor)
            ->get()
            ->map(function($kelas) {
                $omzet = TransaksiDetail::where('id_kelas', $kelas->id_kelas)
                    ->whereHas('transaksi', function($q) { $q->where('status', 'paid'); })
                    ->sum('harga_saat_beli');
                
                $terjual = TransaksiDetail::where('id_kelas', $kelas->id_kelas)
                    ->whereHas('transaksi', function($q) { $q->where('status', 'paid'); })
                    ->count();

                return [
                    'nama_kelas' => $kelas->nama_kelas,
                    'harga' => $kelas->harga,
                    'terjual' => $terjual,
                    'pendapatan' => (int) $omzet
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_saldo' => (int) $totalPendapatan,
                'list_pendapatan_kelas' => $rincian
            ]
        ]);
    }

    /**
     * 3. DETAIL REVIEW
     * Untuk Halaman "Ulasan Siswa"
     */
    public function getReviews(Request $request)
    {
        $user = $request->user();
        $mentor = $this->getMentor($user);
        if (!$mentor) return response()->json(['success' => false], 403);

        $kelasIds = Kelas::where('id_mentor', $mentor->id_mentor)->pluck('id_kelas');

        $reviews = Review::whereIn('id_kelas', $kelasIds)
            ->with(['user:id_user,fullname,foto_profil', 'kelas:id_kelas,nama_kelas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}