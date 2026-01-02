<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Statistik User
        $totalStudent = \App\Models\User::where('role', \App\Models\User::ROLE_USER)->count();
        $totalMentor = \App\Models\User::where('role', \App\Models\User::ROLE_MENTOR)->count();

        // 2. Statistik Kelas
        $totalKelas = \App\Models\Kelas::count();

        // 3. Total Pendapatan (Hanya yang status 'paid' atau 'success' - sesuaikan dengan status enum Anda)
        // Anggap status sukses adalah 'paid' atau 'settlement' atau sejenisnya. Saya gunakan 'paid' berdasarkan asumsi umum, sesuaikan jika perlu.
        $totalPendapatan = \App\Models\Transaksi::where('status', 'paid')->sum('total_harga');

        // 4. Laporan Perlu Tindakan
        $laporanPending = \App\Models\Report::where('status', 'pending')->count();

        // 5. Transaksi Terbaru (5 data terakhir)
        $transaksiTerbaru = \App\Models\Transaksi::with('user')->latest('tgl_transaksi')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudent',
            'totalMentor',
            'totalKelas',
            'totalPendapatan',
            'laporanPending',
            'transaksiTerbaru'
        ));
    }

    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email/password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
