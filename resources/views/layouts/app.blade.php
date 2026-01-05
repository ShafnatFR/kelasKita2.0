<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KelasKu - Platform Belajar Online')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Warna Biru Utama sesuai referensi */
        .bg-primary { background-color: #0056b3; }
        .text-primary { color: #0056b3; }
        .hover-text-primary:hover { color: #0056b3; }
        .btn-primary { background-color: #0056b3; color: white; }
        .btn-primary:hover { background-color: #004494; }
        .btn-outline-primary { border: 1px solid #0056b3; color: #0056b3; }
        .btn-outline-primary:hover { background-color: #0056b3; color: white; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm sticky top-0 z-50 font-medium">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <i class="fas fa-graduation-cap text-3xl text-primary"></i>
                <span class="text-2xl font-bold text-gray-800">KelasKu</span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-gray-600">
                <a href="{{ route('home') }}" class="hover-text-primary transition">Beranda</a>
                <a href="#" class="hover-text-primary transition">Semua Kursus</a>
                <a href="#" class="hover-text-primary transition">Tentang Kami</a>
                <a href="#" class="hover-text-primary transition">Kontak</a>
            </div>

            <div class="flex items-center space-x-6">
                <button class="text-gray-500 hover-text-primary hidden sm:block">
                    <i class="fas fa-search text-lg"></i>
                </button>

                <a href="{{ route('keranjang.index') }}" class="relative text-gray-500 hover-text-primary">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">2</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn-primary px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg hidden sm:block">
                        Masuk / Daftar
                    </a>
                @else
                    <div class="flex items-center space-x-2 cursor-pointer hover-text-primary">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=0056b3&color=fff" class="w-8 h-8 rounded-full">
                        <span class="font-semibold hidden sm:block">{{ Auth::user()->first_name }}</span>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                 <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    <span class="text-2xl font-bold text-white">KelasKu</span>
                </a>
                <p class="mb-4">Platform belajar online terbaik untuk meningkatkan keahlian Anda di era digital.</p>
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition">Semua Kursus</a></li>
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Kategori Populer</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition">Web Development</a></li>
                    <li><a href="#" class="hover:text-white transition">Desain Grafis</a></li>
                    <li><a href="#" class="hover:text-white transition">Digital Marketing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Hubungi Kami</h4>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt mt-1 text-primary"></i>
                        <span>Jl. Teknologi No. 123, Jakarta, Indonesia</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone-alt text-primary"></i>
                        <span>+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-primary"></i>
                        <span>support@kelasku.com</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} KelasKu. All rights reserved. Designed with <i class="fas fa-heart text-red-500"></i> for Students.</p>
        </div>
    </footer>

</body>
</html>