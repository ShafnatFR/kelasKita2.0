<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Kelas;

class HomeController extends Controller
{
    public function index()
    {

         $apiUrl = config('app.url') . '/api/home';
        $response = Http::get($apiUrl);
        
        $kelas = Kelas::latest()->get(); 

        // Kirim ke View
        return view('Home', compact('kelas'));
    }
}