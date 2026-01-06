<?php

namespace App\Http\Controllers\Api\Murid;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 

class ApiReportController extends Controller
{
    public function store(Request $request, $id_kelas)
    {
        // Menggunakan Validator agar jika gagal, responnya JSON (bukan HTML redirect)
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string',
            'keterangan' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan laporan
        $report = Report::create([
            'id_user' => Auth::id(),
            'id_kelas' => $id_kelas,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'status' => 'open', // Status awal laporan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim ke Admin.',
            'data' => $report
        ], 201);
    }
}