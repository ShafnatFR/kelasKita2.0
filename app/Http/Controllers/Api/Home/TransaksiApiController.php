<?php
namespace App\Http\Controllers\Api\Home;

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
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'id_metode_pembayaran' => 'nullable|exists:metode_pembayaran,id_mp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $keranjang = Keranjang::with('kelas')->where('id_user', $user->id_user)->get();

            if ($keranjang->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            $total = $keranjang->sum(fn($i) => $i->kelas->harga);
            $kodeInvoice = 'INV-' . time() . '-' . rand(100, 999);

            DB::beginTransaction();
            try {
                $trx = Transaksi::create([
                    'id_user' => $user->id_user,
                    'kode_invoice' => $kodeInvoice,
                    'total_harga' => $total,
                    'id_mp' => $request->id_metode_pembayaran,
                    'status' => 'pending'
                ]);

                foreach ($keranjang as $item) {
                    TransaksiDetail::create([
                        'id_transaksi' => $trx->id_transaksi,
                        'id_kelas' => $item->id_kelas,
                        'harga_saat_beli' => $item->kelas->harga
                    ]);
                }

                Keranjang::where('id_user', $user->id_user)->delete();

                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Checkout berhasil',
                    'data' => $trx
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat checkout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id_transaksi): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $trx = Transaksi::where('id_transaksi', $id_transaksi)
                ->where('id_user', $user->id_user)
                ->with('details')
                ->first();

            if (!$trx) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $trx
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentMethods(): JsonResponse
    {
        try {
            $methods = MetodePembayaran::where('is_active', true)->get();
            
            return response()->json([
                'success' => true,
                'data' => $methods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bayar(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'id_transaksi' => 'required|exists:transaksi,id_transaksi',
                'id_metode_pembayaran' => 'nullable|exists:metode_pembayaran,id_mp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $trx = Transaksi::where('id_transaksi', $request->id_transaksi)
                ->where('id_user', $user->id_user)
                ->first();

            if (!$trx) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $trx->update([
                'id_mp' => $request->id_metode_pembayaran ?? $trx->id_mp,
                'status' => 'paid'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil',
                'data' => $trx
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}