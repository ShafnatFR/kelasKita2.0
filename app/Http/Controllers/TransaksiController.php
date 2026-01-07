<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function process(Request $request)
    {
        try {
            // DEBUG: Log incoming request
            \Log::info('Checkout process started', [
                'user' => auth()->id(),
                'session_cart' => session()->get('cart', []),
                'all_session' => $request->session()->all()
            ]);
            
            // --- 1. GET USER ---
            $user = auth()->user();
            
            // Jika tidak login, redirect ke login
            if (!$user) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['status' => 'error', 'message' => 'User tidak terautentikasi'], 401);
                }
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            // --- 2. AMBIL DATA ITEM ---
            $cart = [];
            
            if ($request->wantsJson() || $request->is('api/*')) {
                // API Request: ambil dari JSON body
                $cart = $request->input('items', []);
            } else {
                // Web Request: ambil dari session
                $cart = session()->get('cart', []);
            }

            // Cek Keranjang Kosong
            if (!$cart || !is_array($cart) || count($cart) == 0) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Keranjang kosong'
                    ], 400);
                }
                \Log::info('Cart is empty, redirecting', ['cart' => $cart]);
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
            }

            // --- 3. HITUNG TOTAL ---
            $totalHarga = 0;
            foreach ($cart as $item) {
                $harga = is_array($item) ? ($item['harga'] ?? 0) : ($item->harga ?? 0);
                $totalHarga += $harga;
            }

            // --- 4. SIMPAN KE DATABASE ---
            DB::beginTransaction();
            try {
                $userId = $user->id_user ?? $user->id;
                
                \Log::info('Creating transaction', ['userId' => $userId, 'totalHarga' => $totalHarga]);
                
                $idTransaksi = DB::table('transaksi')->insertGetId([
                    'id_user'       => $userId,
                    'kode_invoice'  => 'INV-' . time() . '-' . rand(100, 999),
                    'total_harga'   => $totalHarga,
                    'status'        => 'pending',
                    'tgl_transaksi' => now(),
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);

                \Log::info('Transaction created successfully', ['idTransaksi' => $idTransaksi, 'cartItems' => count($cart)]);

                // Masukkan detail transaksi
                foreach ($cart as $item) {
                    $idKelas = is_array($item) ? ($item['id_kelas'] ?? null) : ($item->id_kelas ?? null);
                    $harga   = is_array($item) ? ($item['harga'] ?? 0) : ($item->harga ?? 0);

                    if ($idKelas) {
                        DB::table('transaksi_detail')->insert([
                            'id_transaksi'    => $idTransaksi,
                            'id_kelas'        => $idKelas,
                            'harga_saat_beli' => $harga,
                            'created_at'      => now(),
                            'updated_at'      => now()
                        ]);
                        \Log::info('Detail transaksi inserted', ['idKelas' => $idKelas, 'harga' => $harga]);
                    }
                }

                DB::commit();
                \Log::info('Transaction committed successfully', ['idTransaksi' => $idTransaksi]);
                
                \Log::info('About to check request type', ['wantsJson' => $request->wantsJson(), 'is_api' => $request->is('api/*')]);

                // RESPON SUKSES - API
                if ($request->wantsJson() || $request->is('api/*')) {
                    \Log::info('Returning JSON response');
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Transaksi berhasil dibuat',
                        'id_transaksi' => $idTransaksi
                    ], 201);
                }
                
                \Log::info('Proceeding to web response');

                // RESPON SUKSES - WEB
                // Hapus session cart
                session()->forget('cart');
                \Log::info('Cart cleared from session');
                
                // Redirect ke halaman detail transaksi
                \Log::info('Redirecting to transaksi.show', ['idTransaksi' => $idTransaksi]);
                return redirect()->route('transaksi.show', $idTransaksi)->with('success', 'Checkout berhasil! Silakan lanjutkan pembayaran.');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat checkout',
                    'error' => $e->getMessage()
                ], 500);
            }
            \Log::error('Checkout error', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * LIHAT DETAIL TRANSAKSI
     */
    public function show(Request $request, $id)
    {
        try {
            $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();

            if (!$transaksi) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
                }
                abort(404);
            }

            $items = DB::table('transaksi_detail')
                ->join('kelas', 'transaksi_detail.id_kelas', '=', 'kelas.id_kelas')
                ->select('transaksi_detail.*', 'kelas.nama_kelas', 'kelas.thumbnail')
                ->where('transaksi_detail.id_transaksi', $id)
                ->get();

            $metodePembayaran = DB::table('metode_pembayaran')->get();

            // RESPON POSTMAN
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'transaksi' => $transaksi,
                        'items' => $items,
                        'pembayaran' => $metodePembayaran
                    ]
                ], 200);
            }

            return view('Transaksi', compact('transaksi', 'items', 'metodePembayaran'));
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat mengambil detail transaksi',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * PROSES PEMBAYARAN
     */
    public function bayar(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'id_transaksi' => 'required|exists:transaksi,id_transaksi',
                'id_mp' => 'required|exists:metode_pembayaran,id_mp'
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $transaksi = DB::table('transaksi')
                ->where('id_transaksi', $request->id_transaksi)
                ->first();

            if (!$transaksi) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Transaksi tidak ditemukan'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
            }

            // Update transaksi
            DB::table('transaksi')
                ->where('id_transaksi', $request->id_transaksi)
                ->update([
                    'id_mp' => $request->id_mp,
                    'status' => 'paid',
                    'updated_at' => now()
                ]);

            // Respon sukses
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pembayaran berhasil diproses',
                    'id_transaksi' => $request->id_transaksi
                ], 200);
            }

            return redirect()->route('transaksi.show', $request->id_transaksi)
                ->with('success', 'Pembayaran berhasil diproses');

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memproses pembayaran',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}