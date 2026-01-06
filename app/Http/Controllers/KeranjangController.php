<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dari Session
        $cart = session()->get('cart', []);

        // Hitung Total
        $total = 0;
        foreach($cart as $item) {
            $total += $item['harga'];
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data keranjang berhasil diambil',
                'total_harga' => $total,
                'data' => $cart
            ], 200);
        }

        // Jika request dari Browser biasa
        return view('Keranjang', compact('cart', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        // Ambil data kelas dari DB
        $kelas = DB::table('kelas')
                ->leftJoin('users', 'kelas.id_mentor', '=', 'users.id_user')
                ->select('kelas.*', 'users.first_name', 'users.last_name')
                ->where('kelas.id_kelas', $id)
                ->first();

        // Cek jika kelas tidak ada
        if(!$kelas) {
            $pesan = 'Kelas tidak ditemukan!';
            
            // Response API Error
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $pesan], 404);
            }
            // Response Web Error
            return redirect()->back()->with('error', $pesan);
        }

        $cart = session()->get('cart', []);

        // Cek Duplikasi
        if(isset($cart[$id])) {
            $pesan = 'Kelas ini sudah ada di keranjang Anda!';
            
            // Response API Warning
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'warning', 'message' => $pesan], 409);
            }
            // Response Web Warning
            return redirect()->route('cart.index')->with('warning', $pesan);
        }

        // Masukkan ke Session
        $cart[$id] = [
            "id_kelas" => $kelas->id_kelas,
            "nama_kelas" => $kelas->nama_kelas,
            "harga" => $kelas->harga,
            "thumbnail" => $kelas->thumbnail,
            "mentor" => $kelas->first_name . ' ' . $kelas->last_name
        ];

        session()->put('cart', $cart);

        $pesanSukses = 'Kelas berhasil masuk keranjang!';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => $pesanSukses,
                'data' => $cart[$id] // Tampilkan data yang barusan masuk
            ], 200);
        }

        // Jika Browser
        return redirect()->route('cart.index')->with('success', $pesanSukses);
    }
    
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        $pesan = 'Kelas dihapus dari keranjang';

        // --- LOGIKA HYBRID ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success', 
                'message' => $pesan,
                'sisa_keranjang' => session()->get('cart')
            ], 200);
        }

        return redirect()->back()->with('success', $pesan);
        
        return view('keranjang', compact('cart', 'total'));
    }
}