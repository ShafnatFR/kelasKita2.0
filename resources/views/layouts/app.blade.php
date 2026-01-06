<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KelasKita 2.0')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Warna Primary disesuaikan dengan Login Page (Blue-600) */
        .text-primary { color: #2563eb; } 
        .bg-primary { background-color: #2563eb; }
        .btn-primary { background-color: #2563eb; color: white; }
        .btn-primary:hover { background-color: #1d4ed8; }
    </style>
</head>
<body class="h-full flex flex-col">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="p-2 bg-blue-600 rounded-lg text-white group-hover:bg-blue-700 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-slate-900">KelasKita<span class="text-blue-600">.</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <a href="#" class="hover:text-blue-600 transition">Kategori</a>
                <a href="#" class="hover:text-blue-600 transition">Mentor</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-500 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @auth
                        <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 rounded-full text-[10px] font-bold text-white flex items-center justify-center border-2 border-white">2</span>
                    @endauth
                </a>

                @guest
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-slate-900 font-semibold hover:text-blue-600 text-sm transition">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">Daftar Sekarang</a>
                    </div>
                @else
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-bold text-slate-900">{{ Auth::user()->first_name }}</div>
                            <div class="text-xs text-slate-500">{{ Auth::user()->role ?? 'Student' }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm">
                            {{ substr(Auth::user()->first_name, 0, 1) }}
                        </div>
                         <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 font-bold ml-2 hover:underline">Keluar</button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 pt-12 pb-8 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="text-xl font-bold text-slate-900">KelasKita<span class="text-blue-600">.</span></span>
            </div>
            <p class="text-slate-500 text-sm mb-6">&copy; {{ date('Y') }} KelasKita. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>