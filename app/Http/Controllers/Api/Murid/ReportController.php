<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiReportController extends Controller
{
    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'kategori' => 'required|string',
            'keterangan' => 'required|string|min:10',
        ]);

        $report = Report::create([
            'id_user' => Auth::id(),
            'id_kelas' => $id_kelas,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim ke Admin.',
            'data' => $report
        ], 201);
    }
}