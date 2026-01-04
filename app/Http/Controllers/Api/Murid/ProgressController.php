<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\ProgressSubMateri;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiProgressController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $purchasedClasses = TransaksiDetail::whereHas('transaksi', function ($query) use ($userId) {
            $query->where('id_user', $userId)->where('status', 'paid');
        })->with(['kelas.materi.subMateri'])->get();

        $data = $purchasedClasses->map(function ($item) use ($userId) {
            $totalSub = 0;
            if ($item->kelas && $item->kelas->materi) {
                foreach ($item->kelas->materi as $bab) {
                    $totalSub += $bab->subMateri->whereNotNull('id_video')->count();
                    $totalSub += $bab->subMateri->whereNotNull('id_dokumen')->count();
                }
            }

            $doneSub = ProgressSubMateri::where('id_user', $userId)
                ->where('id_kelas', $item->id_kelas)
                ->where('is_completed', true)
                ->count();

            return [
                'id_kelas' => $item->id_kelas,
                'nama_kelas' => $item->kelas->nama_kelas ?? 'N/A',
                'progress_percent' => ($totalSub > 0) ? (int)round(($doneSub / $totalSub) * 100) : 0,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show($id_kelas)
    {
        $userId = Auth::id();
        $kelas = Kelas::with(['materi.subMateri.video', 'materi.subMateri.dokumen', 'mentor.user'])
            ->findOrFail($id_kelas);

        $completedMateri = ProgressSubMateri::where('id_user', $userId)
            ->where('id_kelas', $id_kelas)
            ->where('is_completed', true)
            ->pluck('id_sub_materi')->toArray();

        $totalTampil = 0;
        foreach ($kelas->materi as $bab) {
            if ($bab->subMateri->whereNotNull('id_video')->first()) $totalTampil++;
            if ($bab->subMateri->whereNotNull('id_dokumen')->first()) $totalTampil++;
        }

        $percentage = ($totalTampil > 0) ? (int)round((count($completedMateri) / $totalTampil) * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'kelas' => $kelas,
                'completed_materi_ids' => $completedMateri,
                'percentage' => $percentage
            ]
        ]);
    }

    public function toggle(Request $request)
    {
        $progress = ProgressSubMateri::updateOrCreate(
            [
                'id_user' => Auth::id(), 
                'id_kelas' => $request->id_kelas, 
                'id_sub_materi' => $request->id_sub_materi
            ],
            ['is_completed' => filter_var($request->is_completed, FILTER_VALIDATE_BOOLEAN)]
        );

        return response()->json(['success' => true, 'data' => $progress]);
    }
}