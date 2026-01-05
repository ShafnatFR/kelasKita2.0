<?php

namespace App\Http\Controllers\Api\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\SubMateri;
use App\Models\Video;
use App\Models\Dokumen;

class SubMateriController extends Controller
{
    // === FORM TAMBAH KONTEN ===
    public function create($id_kelas, $id_materi)
    {
        $user = Auth::user();
        
        // Validasi: Pastikan kelas milik mentor yang login
        $kelas = Kelas::where('id_kelas', $id_kelas)
                      ->where('id_mentor', $user->mentor->id_mentor)
                      ->firstOrFail();
                      
        // Validasi: Pastikan Bab (Materi) ada di kelas tersebut
        $materi = Materi::where('id_materi', $id_materi)
                        ->where('id_kelas', $id_kelas)
                        ->firstOrFail();

        return view('mentor.submateri.create', compact('user', 'kelas', 'materi'));
    }

    // === PROSES SIMPAN KONTEN ===
    public function store(Request $request, $id_kelas, $id_materi)
    {
        // 1. Validasi Input
        $request->validate([
            'judul_sub'    => 'required|string|max:255',
            'tipe_konten'  => 'required|in:video,dokumen',
            
            // Validasi URL Youtube (Wajib jika pilih video)
            'video_url'    => 'required_if:tipe_konten,video|nullable|url',
            
            // Validasi Dokumen (Wajib jika pilih dokumen)
            'dokumen_file' => 'required_if:tipe_konten,dokumen|nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'teks_pembelajaran' => 'nullable|string',
        ]);

        // Cek Urutan Terakhir
        $lastOrder = SubMateri::where('id_materi', $id_materi)->max('urutan') ?? 0;
        
        $idVideo = null;
        $idDokumen = null;

        // 2. LOGIKA SIMPAN YOUTUBE (Sesuai Model Video Anda)
        if ($request->tipe_konten == 'video' && $request->video_url) {
            
            // Ambil ID Youtube dari Link
            $youtubeId = $this->getYoutubeId($request->video_url);
            
            // Buat Link Embed
            $embedUrl = 'https://www.youtube.com/embed/' . $youtubeId;

            // Simpan ke Tabel 'videos' 
            // Kolom 'file_path' diisi URL Embed sesuai request Anda
            $video = Video::create([
                'file_path' => $embedUrl, 
                'durasi'    => '00:00', // Default (atau bisa tambah input durasi di form jika mau)
            ]);
            
            $idVideo = $video->id_video;
        }

        // 3. LOGIKA UPLOAD DOKUMEN (Tetap sama)
        if ($request->tipe_konten == 'dokumen' && $request->hasFile('dokumen_file')) {
            $file = $request->file('dokumen_file');
            $filename = time() . '_' . \Str::random(5) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumens'), $filename);

            $dokumen = Dokumen::create([
                'judul_dokumen' => $request->judul_sub,
                'file_dokumen'  => 'uploads/dokumens/' . $filename,
                'tipe_file'     => $file->getClientOriginalExtension(),
            ]);
            $idDokumen = $dokumen->id_dokumen;
        }

        // 4. SIMPAN KE TABEL UTAMA (sub_materi)
        SubMateri::create([
            'id_materi'         => $id_materi,
            'id_video'          => $idVideo,
            'id_dokumen'        => $idDokumen,
            'urutan'            => $lastOrder + 1,
            'judul_sub'         => $request->judul_sub, // Judul disimpan di sini
            'teks_pembelajaran' => $request->teks_pembelajaran,
        ]);

        return redirect()->route('mentor.materi.index', $id_kelas)
                         ->with('success', 'Konten berhasil ditambahkan!');
    }

    // === HELPER: AMBIL ID YOUTUBE ===
    // (Simpan function ini di paling bawah class controller)
    private function getYoutubeId($url)
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }
        return null;
    }

    public function edit($id_kelas, $id_materi, $id_sub_materi)
    {
        $user = Auth::user();

        // 1. Validasi Kepemilikan Kelas
        $kelas = Kelas::where('id_kelas', $id_kelas)
            ->where('id_mentor', $user->mentor->id_mentor)
            ->firstOrFail();

        // 2. Ambil Data Sub Materi beserta relasinya (Video/Dokumen)
        $subMateri = SubMateri::with(['materi', 'video', 'dokumen']) // Asumsi Anda sudah buat relasi di model SubMateri
            ->where('id_sub_materi', $id_sub_materi)
            ->where('id_materi', $id_materi)
            ->firstOrFail();

        return view('mentor.submateri.edit', compact('kelas', 'subMateri'));
    }

    public function update(Request $request, $id_kelas, $id_materi, $id_sub_materi)
    {
        $subMateri = SubMateri::where('id_sub_materi', $id_sub_materi)->firstOrFail();

        // 1. Validasi
        $request->validate([
            'judul_sub'         => 'required|string|max:255',
            'teks_pembelajaran' => 'nullable|string',
            // Video URL & Dokumen File bersifat NULLABLE (Tidak wajib diisi jika tidak ingin diganti)
            'video_url'         => 'nullable|url',
            'dokumen_file'      => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
        ]);

        // 2. Update Data Utama
        $subMateri->update([
            'judul_sub'         => $request->judul_sub,
            'teks_pembelajaran' => $request->teks_pembelajaran,
        ]);

        // 3. Update Video (Jika ada input link baru & tipe awalnya video)
        if ($subMateri->id_video && $request->video_url) {
            $video = Video::find($subMateri->id_video);
            if ($video) {
                $youtubeId = $this->getYoutubeId($request->video_url);
                $embedUrl = 'https://www.youtube.com/embed/' . $youtubeId;
                
                $video->update([
                    'file_path' => $embedUrl // Sesuai kolom Anda
                ]);
            }
        }

        if ($subMateri->id_dokumen && $request->hasFile('dokumen_file')) {
            $dokumen = Dokumen::find($subMateri->id_dokumen);
            if ($dokumen) {
                // Hapus file lama fisik
                if (file_exists(public_path($dokumen->file_dokumen))) {
                    unlink(public_path($dokumen->file_dokumen));
                }

                // Upload file baru
                $file = $request->file('dokumen_file');
                $filename = time() . '_' . \Str::random(5) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/dokumens'), $filename);

                // Update Database
                $dokumen->update([
                    'file_dokumen' => 'uploads/dokumens/' . $filename,
                    'tipe_file'    => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('mentor.materi.index', $id_kelas)
                         ->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroy($id_kelas, $id_materi, $id_sub_materi)
    {
        $subMateri = SubMateri::where('id_sub_materi', $id_sub_materi)->firstOrFail();

        // Hapus File Fisik Dokumen jika ada
        if ($subMateri->id_dokumen) {
             $dokumen = Dokumen::find($subMateri->id_dokumen);
             if ($dokumen && file_exists(public_path($dokumen->file_dokumen))) {
                 unlink(public_path($dokumen->file_dokumen));
             }
             // Data di tabel dokumens akan terhapus otomatis jika Anda pakai onCascadeDelete di migrasi
             // Jika tidak, hapus manual: $dokumen->delete();
        }

        // Hapus Data Video (Hanya data database karena file-nya link youtube)
        if ($subMateri->id_video) {
             $video = Video::find($subMateri->id_video);
             $video->delete();
        }

        $subMateri->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }
}