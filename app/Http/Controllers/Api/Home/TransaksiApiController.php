<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiDetail; // Asumsi model ini ada
use App\Models\Keranjang;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class TransaksiApiController extends Controller
{
    public function checkout(Request $request): JsonResponse
    {
        $userId = $request->id_user;
        $keranjang = Keranjang::with('kelas')->where('id_user', $userId)->get();

        if ($keranjang->isEmpty()) return response()->json(['success'=>false], 400);

        $total = $keranjang->sum(fn($i) => $i->kelas->harga);
        // Generate Invoice INV-TIMESTAMP-RAND
        $kodeInvoice = 'INV-' . time() . '-' . rand(100,999);

        DB::beginTransaction();
        try {
            // Buat Transaksi sesuai struktur tabel
            $trx = Transaksi::create([
                'id_user' => $userId,
                'kode_invoice' => $kodeInvoice,
                'total_harga' => $total,
                'status' => 'pending' // Default enum pending
            ]);

            // Pindahkan item keranjang ke detail transaksi
            // Asumsi tabel transaksi_detail punya: id_transaksi, id_kelas, harga_saat_beli
            foreach ($keranjang as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $trx->id_transaksi, // Pakai id_transaksi (PK)
                    'id_kelas' => $item->id_kelas,
                    'harga_saat_beli' => $item->kelas->harga
                ]);
            }

            // Hapus Keranjang
            Keranjang::where('id_user', $userId)->delete();

            DB::commit();
            return response()->json(['success' => true, 'data' => $trx]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id_transaksi): JsonResponse
    {
        // Ambil transaksi
        $trx = Transaksi::where('id_transaksi', $id_transaksi)->first();
        return response()->json(['success' => true, 'data' => $trx]);
    }

    public function getPaymentMethods(): JsonResponse
    {
        // Ambil metode pembayaran aktif
        $methods = MetodePembayaran::where('is_active', true)->get();
        return response()->json(['success' => true, 'data' => $methods]);
    }

    public function bayar(Request $request): JsonResponse
    {
        $idTransaksi = $request->id_transaksi;
        $idMp = $request->id_mp;

        $trx = Transaksi::where('id_transaksi', $idTransaksi)->first();
        
        // Update metode pembayaran dan status jadi paid
        $trx->update([
            'id_mp' => $idMp,
            'status' => 'paid'
        ]);

        return response()->json(['success' => true]);
    }
}