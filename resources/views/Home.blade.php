@extends('layouts.app')

@section('title', 'Beranda - KelasKu')

@section('content')
<section class="bg-primary py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Jelajahi Kursus Terbaik</h1>
        <nav class="flex justify-center text-blue-100 text-sm font-medium space-x-2">
            <span>Beranda</span>
            <span>/</span>
            <span class="text-white">Semua Kursus</span>
        </nav>
    </div>
</section>

<section class="py-16 container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Semua Kursus Tersedia</h2>
        <div class="flex flex-wrap justify-center gap-3">
            <button class="btn-primary px-5 py-2 rounded-full text-sm font-semibold shadow-sm transition">Semua</button>
            <button class="bg-white text-gray-600 hover:bg-gray-100 px-5 py-2 rounded-full text-sm font-semibold shadow-sm border border-gray-200 transition">Web Design</button>
            <button class="bg-white text-gray-600 hover:bg-gray-100 px-5 py-2 rounded-full text-sm font-semibold shadow-sm border border-gray-200 transition">Development</button>
            <button class="bg-white text-gray-600 hover:bg-gray-100 px-5 py-2 rounded-full text-sm font-semibold shadow-sm border border-gray-200 transition">Marketing</button>
        </div>
    </div>

    @if(isset($kelas) && count($kelas) > 0) 
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($kelas as $item) 
    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 group border border-gray-100 flex flex-col h-full">
        <div class="relative">
            {{-- PERBAIKAN: Gunakan ->thumbnail --}}
            <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : 'https://via.placeholder.com/400x250?text=KelasKu' }}" alt="{{ $item->nama_kelas }}" class="w-full h-56 object-cover">
            
            <div class="absolute top-4 right-4 bg-white text-primary font-bold px-4 py-1.5 rounded-full shadow-md text-sm">
                {{-- PERBAIKAN: Gunakan ->harga --}}
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </div>
        </div>

        <div class="p-6 flex-1 flex flex-col">
            <h3 class="text-xl font-bold text-gray-800 mb-3 leading-snug">
                {{-- PERBAIKAN: Gunakan ->id_kelas (Pastikan nama kolom di DB id_kelas atau id) --}}
                <a href="{{ route('Detail_kelas', $item->id_kelas ?? $item->id) }}" class="hover:text-primary transition">
                    {{-- PERBAIKAN: Gunakan ->nama_kelas --}}
                    {{ $item->nama_kelas }}
                </a>
            </h3>
            
            {{-- PERBAIKAN: Gunakan ->description --}}
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $item->description ?? 'Deskripsi kelas...' }}</p>
            
            <div class="mt-auto pt-4 border-t border-gray-100">
                {{-- PERBAIKAN: Gunakan ->id_kelas --}}
                <a href="{{ route('Detail_kelas', $item->id_kelas ?? $item->id) }}" class="text-primary font-bold text-sm hover:underline">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
    </div>
    @else
    <div class="text-center py-20">
        <h3 class="text-xl font-bold text-gray-700">Belum Ada Kursus</h3>
        <p class="text-gray-500">Silakan input data kelas melalui Database secara manual.</p>
    </div>
    @endif
</section>

<section class="container mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="bg-primary rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-xl">
         <div class="absolute -top-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full"></div>
         <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full"></div>

        <div class="text-white md:w-1/2 mb-8 md:mb-0 relative z-10">
            <h2 class="text-3xl font-bold mb-4">Berlangganan Newsletter Kami</h2>
            <p class="text-blue-100 text-lg">Dapatkan info terbaru tentang kursus, promo spesial, dan tips belajar langsung ke inbox Anda.</p>
        </div>
        <div class="md:w-5/12 relative z-10">
            <form class="flex shadow-lg rounded-full overflow-hidden">
                <input type="email" placeholder="Masukkan email Anda..." class="flex-grow px-6 py-4 focus:outline-none text-gray-700">
                <button type="button" class="bg-gray-900 text-white px-8 py-4 font-bold hover:bg-gray-800 transition">Langganan</button>
            </form>
        </div>
    </div>
</section>
@endsection