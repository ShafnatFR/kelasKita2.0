<?php
namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;

class HomeApiController extends Controller
{
    public function index(): JsonResponse
    {
        
        $kelas = Kelas::with('mentor')
            ->where('status_publikasi', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $kelas
        ], 200);
    }
}