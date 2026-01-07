<?php

namespace App\Http\Controllers\Api\Admin;

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

        $reports = $query->with('user')->latest()->paginate(10);

        return response()->json($reports);
    }

    public function show($id)
    {
        $report = Report::with(['user', 'adminNote'])->findOrFail($id);
        
        return response()->json($report);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui.',
            'data' => $report
        ]);
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

        return response()->json([
            'message' => 'Catatan admin berhasil disimpan.'
        ]);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.'
        ]);
    }
}
