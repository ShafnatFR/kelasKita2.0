<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'kategori' => 'required|string',
            'keterangan' => 'required|string|min:10',
        ]);

        // Mengacu pada Model Report: id_user, id_kelas, kategori, keterangan, status
        Report::create([
            'id_user' => Auth::id(),
            'id_kelas' => $id_kelas,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'status' => 'open', // Default sesuai Migration
        ]);

        return back()->with('success', 'Laporan konten telah dikirim ke Admin untuk ditinjau.');
    }
}