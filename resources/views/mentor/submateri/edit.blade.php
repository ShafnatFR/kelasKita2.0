<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konten - {{ $kelas->nama_kelas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-900">
    <div class="flex min-h-screen">
        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                <div class="max-w-3xl mx-auto">
                    
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Konten Materi</h1>
                        <p class="text-slate-500">Ubah judul atau ganti file materi.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
                        <form action="{{ route('mentor.materi.sub.update', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $subMateri->id_materi, 'id_sub_materi' => $subMateri->id_sub_materi]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Materi</label>
                                <input type="text" name="judul_sub" value="{{ old('judul_sub', $subMateri->judul_sub) }}" required
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                            </div>

                            @if($subMateri->id_video)
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-2 mb-3 text-blue-800 font-bold">
                                    <i class="fab fa-youtube"></i> Tipe: Video Youtube
                                </div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ganti Link Video (Opsional)</label>
                                <input type="url" name="video_url" placeholder="Biarkan kosong jika tidak ingin mengganti video"
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-white">
                                <p class="text-xs text-slate-500 mt-2">
                                    Link saat ini: <a href="{{ $subMateri->video->file_path }}" target="_blank" class="text-blue-600 hover:underline">{{ $subMateri->video->file_path }}</a>
                                </p>
                            </div>
                            @endif

                            @if($subMateri->id_dokumen)
                            <div class="bg-orange-50 p-4 rounded-xl border border-orange-100">
                                <div class="flex items-center gap-2 mb-3 text-orange-800 font-bold">
                                    <i class="fas fa-file-pdf"></i> Tipe: Dokumen
                                </div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ganti File Dokumen (Opsional)</label>
                                <input type="file" name="dokumen_file" accept=".pdf,.doc,.docx,.ppt,.pptx"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 border border-slate-300 rounded-xl bg-white">
                                <p class="text-xs text-slate-500 mt-2">
                                    File saat ini: <a href="{{ asset($subMateri->dokumen->file_dokumen) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File Lama</a>
                                </p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teks / Catatan</label>
                                <textarea name="teks_pembelajaran" rows="4" class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">{{ old('teks_pembelajaran', $subMateri->teks_pembelajaran) }}</textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                                <a href="{{ route('mentor.materi.index', $kelas->id_kelas) }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl">Batal</a>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>