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
        // 1. CEK USER
        $user = Auth::user();
        if (!$user) {
            dd("Error: User belum login / Sesi habis."); // DEBUG 1
        }

        // 2. AMBIL DATA KERANJANG
        $cart = [];
        if ($request->wantsJson() || $request->is('api/*')) {
            $cart = $request->input('items'); 
        } else {
            $cart = session('cart');
        }

        // DEBUG 2: Cek isi keranjang
        if (!$cart || count($cart) <= 0) {
            dd("Error: Keranjang Kosong / Tidak Terbaca.", session()->all());
        }

        // 3. HITUNG TOTAL
        $totalHarga = 0;
        foreach ($cart as $item) {
            $harga = is_array($item) ? $item['harga'] : $item->harga; 
            $totalHarga += $harga;
        }

        // 4. INSERT DATABASE (TANPA TRY-CATCH AGAR ERROR KELIHATAN)
        // Kita gunakan DB::transaction agar aman
        $idTransaksi = DB::transaction(function () use ($user, $totalHarga, $cart) {
            
            // A. Insert Transaksi
            // Perhatikan: Pastikan kolom 'id_user' di tabel users Anda benar-benar 'id_user' atau 'id'
            $userId = $user->id_user ?? $user->id;

            if(!$userId) {
                dd("Error: ID User tidak ditemukan. Cek Model User & Primary Key.");
            }

            $idTrans = DB::table('transaksi')->insertGetId([
                'id_user'       => $userId,
                'kode_invoice'  => 'INV-' . strtoupper(uniqid()),
                'total_harga'   => $totalHarga,
                'status'        => 'pending',
                'id_mp'         => null, // Kita set null dulu sesuai struktur tabel Anda
                'tgl_transaksi' => now(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            // B. Insert Detail
            foreach ($cart as $item) {
                $idKelas = is_array($item) ? $item['id_kelas'] : $item->id_kelas;
                $harga   = is_array($item) ? $item['harga'] : $item->harga;

                DB::table('transaksi_detail')->insert([
                    'id_transaksi'    => $idTrans,
                    'id_kelas'        => $idKelas,
                    'harga_saat_beli' => $harga,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]);
            }

            return $idTrans;
        });

        // 5. BERHASIL
        // Hapus session cart jika bukan API
        if (!($request->wantsJson() || $request->is('api/*'))) {
            session()->forget('cart');
        }

        // --- RESPONSE API ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'id_transaksi' => $idTransaksi,
                    'total_harga' => $totalHarga,
                    'invoice' => 'INV-' . time()
                ]
            ], 201);
        }

        // --- RESPONSE WEB ---
        return redirect()->route('transaksi.show', $idTransaksi)->with('success', 'Pesanan berhasil dibuat!');
    }

    public function show(Request $request, $id)
    {
        $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();

        if (!$transaksi) {
            $pesan = 'Transaksi tidak ditemukan';
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $pesan], 404);
            }
            abort(404, $pesan);
        }

        $items = DB::table('transaksi_detail')
            ->join('kelas', 'transaksi_detail.id_kelas', '=', 'kelas.id_kelas')
            ->select('transaksi_detail.*', 'kelas.nama_kelas', 'kelas.thumbnail')
            ->where('transaksi_detail.id_transaksi', $id)
            ->get();

        $metodePembayaran = DB::table('metode_pembayaran')->where('is_active', 1)->get();

        // --- RESPONSE API ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'transaksi' => $transaksi,
                    'items' => $items,
                    'metode_pembayaran' => $metodePembayaran
                ]
            ], 200);
        }

        return view('Transaksi', compact('transaksi', 'items', 'metodePembayaran'));
    }

    // Method untuk menambahkan item ke cart (add to cart)
    public function addToCart(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'harga' => 'required|numeric',
        ]);

        // Ambil cart dari session, jika tidak ada buat array kosong
        $cart = session('cart', []);
        
        // Tambahkan item baru ke cart
        $cart[] = [
            'id_kelas' => $request->id_kelas,
            'harga' => $request->harga,
            // data lain bisa ditambahkan disini jika diperlukan
        ];
        
        // Simpan kembali ke session
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan ke keranjang');
    }

    // Method untuk proses pembayaran
    public function bayar(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id_transaksi',
            'id_metode_pembayaran' => 'required',
            'bukti_bayar' => 'nullable|image|max:2048',
        ]);
        
        try {
            DB::table('transaksi')
                ->where('id_transaksi', $request->id_transaksi)
                ->update([
                    'status' => 'menunggu_konfirmasi',
                    'id_metode_pembayaran' => $request->id_metode_pembayaran,
                    'bukti_bayar' => $request->file('bukti_bayar') 
                        ? $request->file('bukti_bayar')->store('bukti_bayar', 'public') 
                        : null,
                    'updated_at' => now()
                ]);
            
            return redirect()->back()->with('success', 'Pembayaran berhasil diupload');
            } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload pembayaran: ' . $e->getMessage());
        }
    }
}
    