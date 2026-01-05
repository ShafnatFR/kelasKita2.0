<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Konten - {{ $kelas->nama_kelas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-900">

    <div class="flex min-h-screen">
        
        <aside class="w-72 bg-slate-900 text-white flex flex-col shrink-0 hidden lg:flex sticky top-0 h-screen z-50">
            <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-950">
                <span class="text-xl font-bold tracking-tight">KelasKita<span class="text-blue-500">.</span></span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('mentor.materi.index', $kelas->id_kelas) }}" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-arrow-left w-5 h-5 mr-3"></i> Kembali ke Kurikulum
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="max-w-3xl mx-auto">
                    <div class="mb-8">
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                            <span>{{ $kelas->nama_kelas }}</span> 
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="font-medium text-blue-600">{{ $materi->judul_materi }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900">Tambah Konten Materi</h1>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <form action="{{ route('mentor.materi.sub.store', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi]) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Materi</label>
                                <input type="text" name="judul_sub" required placeholder="Contoh: Video Instalasi Software"
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Tipe Konten</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_konten" value="video" class="peer sr-only" checked onchange="toggleInput('video')">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center justify-center gap-3 text-slate-600 peer-checked:text-blue-700">
                                            <i class="fas fa-play-circle text-xl"></i>
                                            <span class="font-bold">Video</span>
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_konten" value="dokumen" class="peer sr-only" onchange="toggleInput('dokumen')">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center justify-center gap-3 text-slate-600 peer-checked:text-blue-700">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                            <span class="font-bold">Dokumen / PDF</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                         <div id="input-video">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Link Video YouTube</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fab fa-youtube text-red-500 text-lg"></i>
                                </div>
                                <input type="url" name="video_url" placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 pl-10 pr-4 bg-slate-50">
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Masukkan link video YouTube, sistem akan otomatis mengubahnya menjadi format embed.</p>
                        </div>

                            <div id="input-dokumen" class="hidden">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Dokumen (PDF/PPT)</label>
                                <input type="file" name="dokumen_file" accept=".pdf,.doc,.docx,.ppt,.pptx"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-xl">
                                <p class="text-xs text-slate-400 mt-2">Maksimal ukuran file: 20MB.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teks / Catatan Tambahan (Opsional)</label>
                                <textarea name="teks_pembelajaran" rows="4" placeholder="Tulis catatan penting untuk siswa di sini..."
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50"></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                                <a href="{{ route('mentor.materi.index', $kelas->id_kelas) }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl">Batal</a>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">Simpan Konten</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function toggleInput(type) {
            const videoInput = document.getElementById('input-video');
            const docInput = document.getElementById('input-dokumen');

            if (type === 'video') {
                videoInput.classList.remove('hidden');
                docInput.classList.add('hidden');
            } else {
                videoInput.classList.add('hidden');
                docInput.classList.remove('hidden');
            }
        }
    </script>

</body>
</html>