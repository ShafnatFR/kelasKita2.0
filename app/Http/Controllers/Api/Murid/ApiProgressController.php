<?php

namespace App\Http\Controllers\Api\Murid;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\ProgressSubMateri;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiProgressController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Mengambil kelas yang sudah dibayar oleh user
        $purchasedClasses = TransaksiDetail::whereHas('transaksi', function ($query) use ($userId) {
            $query->where('id_user', $userId)->where('status', 'paid');
        })->with(['kelas.materi.subMateri'])->get();

        $data = $purchasedClasses->map(function ($item) use ($userId) {
            $totalSub = 0;
            if ($item->kelas && $item->kelas->materi) {
                foreach ($item->kelas->materi as $bab) {
                    // Menghitung total materi yang memiliki video ATAU dokumen
                    $totalSub += $bab->subMateri->count();
                }
            }

            $doneSub = ProgressSubMateri::where('id_user', $userId)
                ->where('id_kelas', $item->id_kelas)
                ->where('is_completed', true)
                ->count();

            return [
                'id_kelas' => $item->id_kelas,
                'nama_kelas' => $item->kelas->nama_kelas ?? 'N/A',
                // Menggunakan min(100) agar persentase tidak melebihi 100% jika ada data ganda
                'progress_percent' => ($totalSub > 0) ? min(100, (int)round(($doneSub / $totalSub) * 100)) : 0,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show($id_kelas)
    {
        $userId = Auth::id();
        
        // Memastikan kelas ada, jika tidak otomatis kirim error 404
        $kelas = Kelas::with(['materi.subMateri.video', 'materi.subMateri.dokumen', 'mentor.user'])
            ->findOrFail($id_kelas);

        // Ambil ID materi yang sudah selesai
        $completedMateri = ProgressSubMateri::where('id_user', $userId)
            ->where('id_kelas', $id_kelas)
            ->where('is_completed', true)
            ->pluck('id_sub_materi')->toArray();

        // Hitung total sub-materi secara keseluruhan
        $totalMateri = 0;
        foreach ($kelas->materi as $bab) {
            $totalMateri += $bab->subMateri->count();
        }

        $percentage = ($totalMateri > 0) ? min(100, (int)round((count($completedMateri) / $totalMateri) * 100)) : 0;

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
        // VALIDASI: Penting agar Postman tidak error jika data kosong
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|integer',
            'id_sub_materi' => 'required|integer',
            'is_completed' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $progress = ProgressSubMateri::updateOrCreate(
            [
                'id_user' => Auth::id(), 
                'id_kelas' => $request->id_kelas, 
                'id_sub_materi' => $request->id_sub_materi
            ],
            ['is_completed' => filter_var($request->is_completed, FILTER_VALIDATE_BOOLEAN)]
        );

        return response()->json([
            'success' => true, 
            'message' => 'Progres berhasil diperbarui',
            'data' => $progress
        ]);
    }
}