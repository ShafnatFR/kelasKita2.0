<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Panggil API
        $apiUrl = config('app.url') . '/api/home';
        $response = Http::get($apiUrl);
        
        $kelas = $response->successful() ? $response->json()['data'] : [];

        return view('home', compact('kelas'));
    }
}