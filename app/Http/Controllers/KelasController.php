<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{

    public function index(Request $request)
    {
        $kelas = DB::table('kelas')->get();

        // JIKA API/POSTMAN
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $kelas
            ]);
        }

        return view('Home', compact('kelas'));
    }

    public function show(Request $request, $id)
    {
        $kelas = DB::table('kelas')
            ->leftJoin('users', 'kelas.id_mentor', '=', 'users.id_user')
            ->select('kelas.*', 'users.first_name', 'users.last_name', 'users.foto_profil', 'users.role')
            ->where('kelas.id_kelas', $id)
            ->first();

        if (!$kelas) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
            }
            abort(404);
        }

        // JIKA API/POSTMAN
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $kelas
            ]);
        }

        return view('Detail_kelas', compact('kelas'));
    }
}