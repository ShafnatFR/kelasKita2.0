<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class KeranjangController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1; // Asumsi ID 1 jika belum login auth
        $apiUrl = config('app.url') . '/api/keranjang';
        
        $response = Http::get($apiUrl, ['id_user' => $userId]);
        $keranjang = $response->successful() ? $response->json()['data'] : [];

        // Hitung Total
        $total = 0;
        foreach ($keranjang as $item) {
            $total += $item['kelas']['harga'] ?? 0;
        }

        return view('keranjang', compact('keranjang', 'total'));
    }

    public function tambah(Request $request)
    {
        $userId = auth()->id() ?? 1;
        $apiUrl = config('app.url') . '/api/keranjang';
        
        Http::post($apiUrl, [
            'id_user' => $userId,
            'id_kelas' => $request->id_kelas
        ]);

        return redirect()->route('keranjang.index');
    }

    public function hapus($id_keranjang)
    {
        $apiUrl = config('app.url') . "/api/keranjang/{$id_keranjang}";
        Http::delete($apiUrl);
        return redirect()->route('keranjang.index');
    }
}