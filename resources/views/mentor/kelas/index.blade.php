<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas - KelasKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-900">

    <div class="flex min-h-screen">
        
        <aside class="w-72 bg-slate-900 text-white flex flex-col shrink-0 hidden lg:flex sticky top-0 h-screen z-50">
            <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-950">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">KelasKita<span class="text-blue-500">.</span></span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ route('mentor.dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-home w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Dashboard
                </a>

                <a href="{{ route('mentor.kelas.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-900/20">
                    <i class="fas fa-chalkboard-teacher w-5 h-5 mr-3 text-blue-200"></i>
                    Kelola Kelas
                </a>

                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-star w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Ulasan Siswa
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Keuangan</p>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-wallet w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Pendapatan
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Akun</p>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-user-circle w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Profil Saya
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-950">
                <div class="flex items-center gap-3 w-full">
                    <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold border border-slate-700">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $user->first_name }}</p>
                        <p class="text-xs text-slate-500 truncate">Mentor</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors"><i class="fas fa-power-off"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 lg:hidden sticky top-0 z-40">
                <span class="font-bold text-slate-800">KelasKita.</span>
                <button class="text-slate-500 text-xl"><i class="fas fa-bars"></i></button>
            </header>

            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Daftar Kelas</h1>
                        <p class="text-slate-500 mt-1">Kelola kelas yang Anda ajarkan di sini.</p>
                    </div>
                    <div>
                        <a href="{{ route('mentor.kelas.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all">
                            <i class="fas fa-plus mr-2"></i> Tambah Kelas
                        </a>
                    </div>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
                    <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($kelasList as $kelas)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                        
                        <div class="h-48 bg-slate-200 relative overflow-hidden">
                            @if($kelas->thumbnail)
                                <img src="{{ asset($kelas->thumbnail) }}" alt="{{ $kelas->nama_kelas }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-slate-700 shadow-sm">
                                Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-bold text-lg text-slate-900 mb-2 line-clamp-1">{{ $kelas->nama_kelas }}</h3>
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $kelas->description }}</p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <a href="{{ route('mentor.materi.index', $kelas->id_kelas) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center">
                                    <i class="fas fa-layer-group mr-2"></i> Materi
                                </a>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('mentor.kelas.edit', $kelas->id_kelas) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('mentor.kelas.destroy', $kelas->id_kelas) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 flex flex-col items-center justify-center text-center bg-white border border-dashed border-slate-300 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4 text-3xl">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Belum ada kelas</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mt-1 mb-6">Anda belum membuat kelas apapun. Mulai bagikan ilmu Anda sekarang.</p>
                        <a href="{{ route('mentor.kelas.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                            Buat Kelas Pertama
                        </a>
                    </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>
</body>
</html>