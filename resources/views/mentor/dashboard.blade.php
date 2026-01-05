<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor - KelasKita</title>
    
    {{-- CSS Framework: Tailwind CDN (Agar tampilan langsung rapi) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
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
                
                {{-- KELOMPOK 1: MENU UTAMA --}}
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ route('mentor.dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-900/20">
                    <i class="fas fa-home w-5 h-5 mr-3 text-blue-200"></i>
                    Dashboard
                </a>

                <a href="{{ route('mentor.kelas.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-chalkboard-teacher w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Kelola Kelas
                </a>

                {{-- Link ini masih '#' karena route-nya belum kita buat --}}
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-star w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Ulasan Siswa
                </a>

                {{-- KELOMPOK 2: KEUANGAN --}}
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Keuangan</p>
                
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-wallet w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Pendapatan
                </a>

                {{-- KELOMPOK 3: PENGATURAN (Akun) --}}
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
                    <div class="flex-1 min-w-0 cursor-pointer hover:opacity-80" onclick="window.location='#'"> {{-- Nanti ganti '#' dengan route profil --}}
                        <p class="text-sm font-medium text-white truncate">{{ $user->first_name }}</p>
                        <p class="text-xs text-slate-500 truncate">Mentor</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors" title="Logout">
                            <i class="fas fa-power-off"></i>
                        </button>
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
                        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                        <p class="text-slate-500 mt-1">Halo {{ $user->first_name }}, pantau performa mengajar Anda di sini.</p>
                    </div>
                    <div>
                        <a href="{{ route('mentor.kelas.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all">
                            <i class="fas fa-plus mr-2"></i> Buat Kelas Baru
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Total</span>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $totalKelas }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Kelas Aktif</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $totalSiswa }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Siswa Terdaftar</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Pendapatan</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900">{{ number_format($avgRating, 1) }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Rating</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4 text-2xl">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900">Belum ada aktivitas</h4>
                        <p class="text-slate-500 max-w-sm mx-auto mt-2">Data transaksi siswa akan muncul di sini.</p>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>