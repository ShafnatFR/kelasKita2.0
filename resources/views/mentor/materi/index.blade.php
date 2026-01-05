<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kurikulum - {{ $kelas->nama_kelas }}</title>
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
                <a href="{{ route('mentor.kelas.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-arrow-left w-5 h-5 mr-3"></i> Kembali ke Kelas
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="mb-8">
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                        <span>Kelas</span> <i class="fas fa-chevron-right text-xs"></i>
                        <span class="font-medium text-blue-600">{{ $kelas->nama_kelas }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">Kurikulum Kelas</h1>
                    <p class="text-slate-500">Atur Bab dan Materi pembelajaran di sini.</p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
                    <form action="{{ route('mentor.materi.store', $kelas->id_kelas) }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="text" name="judul_materi" required placeholder="Judul Bab Baru (Contoh: Bab 1 - Pendahuluan)" 
                            class="flex-1 rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 bg-slate-50">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-medium rounded-lg hover:bg-slate-800 transition-colors">
                            + Tambah Bab
                        </button>
                    </form>
                </div>

                <div class="space-y-6">
                    @forelse($materiList as $materi)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            
                            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-400">#{{ $materi->urutan }}</span>
                                    <h3 class="font-bold text-lg text-slate-800">{{ $materi->judul_materi }}</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    
                                    <a href="{{ route('mentor.materi.edit', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi]) }}" 
                                       class="p-2 text-slate-400 hover:text-blue-600 transition-colors" 
                                       title="Edit Judul Bab">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('mentor.materi.destroy', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi]) }}" method="POST" onsubmit="return confirm('Hapus Bab ini beserta isinya?');">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus Bab"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    
                                    <a href="{{ route('mentor.materi.sub.create', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi]) }}" class="px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-200 ml-2">
                                        + Materi
                                    </a>
                                </div>
                            </div>

                            <div class="divide-y divide-slate-100 bg-white">
                                @forelse($materi->subMateri as $sub)
                                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                        
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-lg shrink-0">
                                                @if($sub->id_video) 
                                                    <i class="fas fa-play-circle"></i>
                                                @elseif($sub->id_dokumen) 
                                                    <i class="fas fa-file-alt"></i>
                                                @else 
                                                    <i class="fas fa-align-left"></i> 
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-col">
                                                <span class="text-slate-700 font-semibold text-sm">{{ $sub->judul_sub }}</span>
                                                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                                    {{ $sub->id_video ? 'Video' : ($sub->id_dokumen ? 'Dokumen' : 'Teks') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('mentor.materi.sub.edit', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi, 'id_sub_materi' => $sub->id_sub_materi]) }}" 
                                               class="text-xs font-semibold text-slate-400 hover:text-blue-600 flex items-center gap-1 transition-colors">
                                               <i class="fas fa-pencil-alt"></i> Edit
                                            </a>

                                            <span class="text-slate-200">|</span>

                                            <form action="{{ route('mentor.materi.sub.destroy', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi, 'id_sub_materi' => $sub->id_sub_materi]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Hapus materi ini?');" 
                                                  class="inline-block">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-600 flex items-center gap-1 transition-colors">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                @empty
                                    <div class="px-6 py-8 flex flex-col items-center justify-center text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-box-open text-slate-300 text-xl"></i>
                                        </div>
                                        <p class="text-sm text-slate-500 font-medium">Belum ada konten materi.</p>
                                        <p class="text-xs text-slate-400">Klik tombol "+ Materi" di kanan atas untuk mengisi bab ini.</p>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Belum ada Bab</h3>
                            <p class="text-slate-500">Mulai dengan menambahkan Bab pertama di atas.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>

</body>
</html>