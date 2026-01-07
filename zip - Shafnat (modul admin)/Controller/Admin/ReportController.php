<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query();

        // Fitur Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'like', '%'.$search.'%')
                    ->orWhere('keterangan', 'like', '%'.$search.'%')
                    ->orWhere('status', 'like', '%'.$search.'%');
            });
        }

        // Filter
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $kategoriUnik = Report::select('kategori')->distinct()->pluck('kategori');
        $statusUnik = Report::select('status')->distinct()->pluck('status');

        $report = $query->get();

        return view('admin.pages.kelola-report.index', compact('report', 'kategoriUnik', 'statusUnik'));
    }
    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
        
        // Ambil daftar report lain untuk sidebar (exclude yg sedang dibuka)
        // Kita ambil 20 terakhir agar tidak terlalu berat
        $sidebarReports = Report::where('id_report', '!=', $id)
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.pages.kelola-report.show', compact('report', 'sidebarReports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string',
        ]);

        $report = Report::findOrFail($id);
        
        if ($report->adminNote) {
            $report->adminNote->update(['content' => $request->catatan_admin]);
        } else {
             $report->adminNote()->create(['content' => $request->catatan_admin]);
        }

        return back()->with('success', 'Catatan admin berhasil disimpan.');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.kelola.report')->with('success', 'Laporan berhasil dihapus.');
    }
}
