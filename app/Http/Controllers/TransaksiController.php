<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{
    public function checkout()
    {
        $userId = auth()->id() ?? 1;
        $apiUrl = config('app.url') . '/api/transaksi/checkout';

        $response = Http::post($apiUrl, ['id_user' => $userId]);

        if ($response->successful()) {
            $idTransaksi = $response->json()['data']['id_transaksi'];
            return redirect()->route('transaksi.show', $idTransaksi);
        }

        return back()->with('error', 'Gagal checkout');
    }

    public function show($id_transaksi)
    {
        $baseUrl = config('app.url') . '/api';
        
        // Ambil data transaksi
        $trxRes = Http::get("$baseUrl/transaksi/$id_transaksi");
        $trx = $trxRes->json()['data'];

        // Ambil data metode pembayaran
        $methodRes = Http::get("$baseUrl/metode-pembayaran");
        $methods = $methodRes->json()['data'];

        return view('transaksi', compact('trx', 'methods'));
    }

    public function bayar(Request $request)
    {
        $apiUrl = config('app.url') . '/api/transaksi/bayar';
        
        Http::post($apiUrl, [
            'id_transaksi' => $request->id_transaksi,
            'id_mp' => $request->id_mp
        ]);

        // Redirect ke home atau halaman sukses
        return redirect()->route('home')->with('success', 'Pembayaran Berhasil!');
    }
}