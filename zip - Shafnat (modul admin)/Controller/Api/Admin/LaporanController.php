<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_invoice', 'like', '%'.$search.'%')
                    ->orWhere('total_harga', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', '%'.$search.'%');
                    });
            });
        }

        // Filter Metode Pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '') {
            $query->whereHas('metodePembayaran', function ($q) use ($request) {
                $q->where('nama_metode', $request->metode_pembayaran);
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != '') {
            $query->whereBetween('tgl_transaksi', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date') && $request->start_date != '') {
            $query->where('tgl_transaksi', '>=', $request->start_date);
        } elseif ($request->has('end_date') && $request->end_date != '') {
            $query->where('tgl_transaksi', '<=', $request->end_date);
        }

        $transaksi = $query->with(['user', 'metodePembayaran'])->latest('tgl_transaksi')->paginate(10);

        return response()->json($transaksi);
    }
}
