<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data kelas + join ke user untuk nama mentor
        $kelas = DB::table('kelas')
            // Gunakan leftJoin agar kalau mentornya hilang/terhapus, kelas TETAP MUNCUL
            ->leftJoin('users', 'kelas.id_mentor', '=', 'users.id_user')
            ->select(
                'kelas.*', 
                'users.first_name', 
                'users.last_name', 
                'users.foto_profil',
                'users.role'
            )
            ->where('kelas.id_kelas', $id)
            ->first();

        // 2. Jika kelas tidak ditemukan, baru 404
        if (!$kelas) {
            abort(404, "Data kelas tidak ditemukan di database.");
        }

        // 3. Panggil View yang ada di folder luar (Detail_kelas.blade.php)
        // Perhatikan penulisan nama file harus SAMA PERSIS
        return view('Detail_kelas', compact('kelas'));
    }
}