<?php

namespace App\Http\Controllers\Api\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Str;
use App\Models\Kelas;


class KelasController extends Controller
{
    // === TAMPILAN WEB: LIST KELAS ===
    public function indexWeb()
    {
        $user = Auth::user();
        
        // PERBAIKAN: Ambil ID Mentor dari relasi user, bukan ID User langsung
        // Karena Foreign Key di tabel kelas adalah 'id_mentor'
        $idMentor = $user->mentor ? $user->mentor->id_mentor : null;

        if (!$idMentor) {
            // Jaga-jaga jika user login tapi datanya belum masuk tabel mentor
            return redirect()->back()->with('error', 'Data mentor tidak ditemukan.');
        }

        $kelasList = Kelas::where('id_mentor', $idMentor)->get();

        return view('mentor.kelas.index', compact('user', 'kelasList'));
    }

    // === TAMPILAN WEB: FORM BUAT KELAS ===
    public function create()
    {
        $user = Auth::user();
        return view('mentor.kelas.create', compact('user'));
    }

    // === PROSES: SIMPAN KELAS BARU ===
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|string', 
            // PERBAIKAN: Sesuaikan dengan 'name' di form HTML (deskripsi)
            'deskripsi'  => 'required|string', 
            'thumbnail'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $pathThumbnail = null;

        // Upload Gambar
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            // Tambahkan random string biar nama file unik tidak bentrok
            $filename = time() . '_' . \Str::random(5) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kelas'), $filename);
            $pathThumbnail = 'uploads/kelas/' . $filename;
        }

        // 2. Simpan ke Database
        Kelas::create([
            // PERBAIKAN: Gunakan ID Mentor dari tabel mentors
            'id_mentor'   => $user->mentor->id_mentor, 
            
            'nama_kelas'  => $request->nama_kelas,
            // Slug dihapus disini karena sudah otomatis dibuat di Model (boot)
            
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            
            // PERBAIKAN: Mapping Input 'deskripsi' ke Kolom Database 'description'
            'description' => $request->deskripsi, 
            
            'thumbnail'   => $pathThumbnail,
            'status_publikasi' => 'draft', // Default status (sesuai fillable model)
        ]);

        return redirect()->route('mentor.kelas.index')->with('success', 'Kelas berhasil dibuat!');
    }

    // ... function store sudah ada di atas ...

    // === TAMPILAN WEB: FORM EDIT KELAS ===
    public function edit($id)
    {
        $user = Auth::user();
        
        // Cari kelas berdasarkan ID, dan pastikan milik mentor yang login
        $kelas = Kelas::where('id_kelas', $id)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();

        return view('mentor.kelas.edit', compact('user', 'kelas'));
    }

    // === PROSES: UPDATE KELAS ===
    public function update(Request $request, $id)
    {
        // 1. Cari dulu kelasnya
        $user = Auth::user();
        $kelas = Kelas::where('id_kelas', $id)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();

        // 2. Validasi (Thumbnail boleh kosong jika tidak ingin diganti)
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|string',
            'deskripsi'  => 'required|string',
            'thumbnail'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 3. Cek apakah ada upload gambar baru?
        $pathThumbnail = $kelas->thumbnail; // Default pakai yang lama

        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama jika ada
            if ($kelas->thumbnail && file_exists(public_path($kelas->thumbnail))) {
                unlink(public_path($kelas->thumbnail));
            }

            // Upload gambar baru
            $file = $request->file('thumbnail');
            $filename = time() . '_' . \Str::random(5) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kelas'), $filename);
            $pathThumbnail = 'uploads/kelas/' . $filename;
        }

        // 4. Update Database
        $kelas->update([
            'nama_kelas'  => $request->nama_kelas,
            // Slug otomatis terupdate di Model jika nama kelas berubah
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'description' => $request->deskripsi, // Mapping input 'deskripsi' ke kolom 'description'
            'thumbnail'   => $pathThumbnail,
        ]);

        return redirect()->route('mentor.kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    // ... function destroy sudah ada di bawah ...

    // === PROSES: HAPUS KELAS ===
    public function destroy($id)
    {
        // Cari kelas berdasarkan ID dan pastikan milik mentor yang sedang login
        $user = Auth::user();
        $kelas = Kelas::where('id_kelas', $id)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();
        
        // Hapus file thumbnail fisik jika ada
        if ($kelas->thumbnail && file_exists(public_path($kelas->thumbnail))) {
            unlink(public_path($kelas->thumbnail));
        }

        $kelas->delete();

        return redirect()->route('mentor.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}