<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\ProgressSubMateri;
use App\Models\Review;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $purchasedClasses = TransaksiDetail::whereHas('transaksi', function ($query) {
            $query->where('id_user', Auth::id())->where('status', 'paid');
        })->with(['kelas.materi.subMateri'])->get();

        foreach ($purchasedClasses as $item) {
            // Hitung total materi yang valid (punya video atau dokumen)
            $totalSub = 0;
            foreach ($item->kelas->materi as $bab) {
                $totalSub += $bab->subMateri->whereNotNull('id_video')->count();
                $totalSub += $bab->subMateri->whereNotNull('id_dokumen')->count();
            }

            $doneSub = ProgressSubMateri::where('id_user', Auth::id())
                ->where('id_kelas', $item->id_kelas)
                ->where('is_completed', true)
                ->count();

            $item->percent = ($totalSub > 0) ? (int)round(($doneSub / $totalSub) * 100) : 0;
        }

        return view('learning.index', compact('purchasedClasses'));
    }

    public function show($id_kelas)
    {
        $kelas = Kelas::with(['materi.subMateri.video', 'materi.subMateri.dokumen', 'mentor.user'])->findOrFail($id_kelas);

        // Ambil materi yang berstatus is_completed = true
        $completedMateri = ProgressSubMateri::where('id_user', Auth::id())
            ->where('id_kelas', $id_kelas)
            ->where('is_completed', true)
            ->pluck('id_sub_materi')->toArray();

        // Hitung total materi yang tampil (1 Video + 1 PDF per BAB)
        $totalTampil = 0;
        foreach ($kelas->materi as $bab) {
            if ($bab->subMateri->whereNotNull('id_video')->first()) $totalTampil++;
            if ($bab->subMateri->whereNotNull('id_dokumen')->first()) $totalTampil++;
        }

        // Jika centang 0, hasil mutlak 0%
        $percentage = ($totalTampil > 0) ? (int)round((count($completedMateri) / $totalTampil) * 100) : 0;

        // Ambil semua ulasan untuk ditampilkan di bawah form
        $allReviews = \App\Models\Review::with('user')->where('id_kelas', $id_kelas)->latest()->get();

        return view('learning.show', compact('kelas', 'completedMateri', 'percentage', 'allReviews'));
    }
    public function toggle(Request $request)
    {
        ProgressSubMateri::updateOrCreate(
            ['id_user' => Auth::id(), 'id_kelas' => $request->id_kelas, 'id_sub_materi' => $request->id_sub_materi],
            ['is_completed' => filter_var($request->is_completed, FILTER_VALIDATE_BOOLEAN)]
        );

        return response()->json(['success' => true]);
    }
}
