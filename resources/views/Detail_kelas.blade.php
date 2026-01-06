@extends('layouts.app')

@section('content')

{{-- BAGIAN 1: HEADER HITAM (Hero Section) --}}
<div class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-6 lg:flex">
        {{-- Kolom Kiri Header --}}
        <div class="lg:w-2/3 lg:pr-8">
            {{-- Breadcrumb --}}
            <div class="text-blue-300 text-sm font-bold mb-4 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a> 
                <i class="fas fa-chevron-right text-xs text-gray-500"></i>
                <span class="text-white">{{ $kelas->kategori ?? 'Web Development' }}</span>
            </div>

            {{-- Judul Kelas --}}
            <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight text-white">
                {{ $kelas->nama_kelas }}
            </h1>
            
            {{-- Deskripsi Singkat --}}
            <p class="text-lg text-gray-300 mb-6 line-clamp-2">
                {{ Str::limit($kelas->description, 150) }}
            </p>

            {{-- Info Tambahan (Rating, Mentor, Update) --}}
            <div class="flex flex-wrap items-center gap-4 text-sm mb-6">
                @if($kelas->status_publikasi == 'draft')
                    <span class="bg-red-500 text-white px-2 py-1 rounded font-bold text-xs uppercase">Mode Draft</span>
                @else
                    <span class="bg-yellow-400 text-gray-900 px-2 py-1 rounded font-bold text-xs uppercase">Bestseller</span>
                @endif
                
                <div class="flex items-center text-yellow-400 font-bold">
                    <span>4.8</span>
                    <div class="ml-1 flex text-xs">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="text-gray-400">
                    Dibuat oleh <span class="text-blue-300 underline cursor-pointer">
                        {{ $kelas->first_name ?? 'Tanpa Nama' }} {{ $kelas->last_name ?? '' }}
                    </span>
                </div>

                <div class="flex items-center text-gray-400 gap-1">
                    <i class="fas fa-exclamation-circle"></i> 
                    Terakhir diperbarui {{ \Carbon\Carbon::parse($kelas->updated_at)->format('M Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN 2: KONTEN UTAMA & SIDEBAR --}}
<div class="container mx-auto px-6 py-12 relative">
    <div class="flex flex-col lg:flex-row">
        
        {{-- KOLOM KIRI (Deskripsi & Materi) --}}
        <div class="w-full lg:w-2/3 lg:pr-12 order-2 lg:order-1 mt-10 lg:mt-0">
            
            {{-- Box: Apa yang dipelajari --}}
            <div class="border border-gray-300 p-6 rounded-lg mb-10 bg-white">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Apa yang akan Anda pelajari</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                    <div class="flex items-start"><i class="fas fa-check text-gray-900 mt-1 mr-3"></i> <span>Memahami konsep dasar hingga mahir.</span></div>
                    <div class="flex items-start"><i class="fas fa-check text-gray-900 mt-1 mr-3"></i> <span>Membangun portofolio proyek nyata.</span></div>
                    <div class="flex items-start"><i class="fas fa-check text-gray-900 mt-1 mr-3"></i> <span>Tips karir dari mentor expert.</span></div>
                    <div class="flex items-start"><i class="fas fa-check text-gray-900 mt-1 mr-3"></i> <span>Sertifikat kelulusan resmi.</span></div>
                </div>
            </div>

            {{-- Deskripsi Lengkap --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Kursus</h2>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($kelas->description)) !!}
                </div>
            </div>

            {{-- Profil Mentor --}}
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Instruktur</h2>
                <div class="flex items-start gap-4">
                     <img src="{{ $kelas->foto_profil ? asset('storage/'.$kelas->foto_profil) : 'https://ui-avatars.com/api/?name='.($kelas->first_name ?? 'User').'&background=random' }}" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-gray-100 shadow-sm">
                    <div>
                        <div class="font-bold text-lg text-blue-700 underline mb-1">
                            {{ $kelas->first_name ?? 'Mentor' }} {{ $kelas->last_name ?? '' }}
                        </div>
                        <div class="text-gray-500 text-sm mb-3">{{ $kelas->role ?? 'Mentor' }} Professional</div>
                        <p class="text-gray-600 text-sm">
                            Mentor berpengalaman yang siap membimbing Anda langkah demi langkah dalam menguasai materi ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (SIDEBAR STICKY / MELAYANG) --}}
        <div class="w-full lg:w-1/3 order-1 lg:order-2 relative">
            {{-- Card ini akan 'melayang' menumpuk header hitam (seperti Udemy) --}}
            <div class="bg-white shadow-2xl border border-gray-200 rounded-lg overflow-hidden lg:absolute lg:-top-80 lg:right-0 w-full z-20 sticky top-4">
                
                {{-- Preview Image / Video --}}
                <div class="relative group cursor-pointer bg-gray-100 border-b border-gray-200">
                    <img src="{{ $kelas->thumbnail ? asset('storage/'.$kelas->thumbnail) : 'https://via.placeholder.com/600x350?text=Preview+Kelas' }}" 
                         class="w-full h-48 object-cover">
                    
                    {{-- Tombol Play Overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-30 transition duration-300">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition">
                            <i class="fas fa-play text-gray-900 ml-1 text-xl"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-4 w-full text-center text-white font-bold text-sm drop-shadow-md">Pratinjau kursus ini</div>
                </div>

                <div class="p-6">
                    {{-- Harga --}}
                    <div class="text-4xl font-bold text-gray-900 mb-6">
                        Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col gap-3">
                         {{-- FORM BELI SEKARANG --}}
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
                            <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 px-4 rounded transition shadow-lg text-lg">
                                Beli Sekarang
                            </button>
                        </form>

                        {{-- FORM TAMBAH KERANJANG --}}
                        <form action="{{ route('cart.add', $kelas->id_kelas) }}" method="POST">
                            @csrf
                            <button class="w-full bg-white hover:bg-gray-50 text-gray-900 border border-gray-900 font-bold py-3.5 px-4 rounded transition">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                    
                    <div class="text-center text-xs text-gray-500 mt-4 mb-6">Jaminan uang kembali 30-Hari</div>
                    
                    {{-- Fitur List --}}
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="font-bold text-gray-900 mb-2">Kursus ini mencakup:</div>
                        <div class="flex items-center"><i class="fas fa-video w-6 text-gray-600 text-center mr-2"></i> Akses seumur hidup</div>
                        <div class="flex items-center"><i class="fas fa-mobile-alt w-6 text-gray-600 text-center mr-2"></i> Akses di HP dan TV</div>
                        <div class="flex items-center"><i class="fas fa-infinity w-6 text-gray-600 text-center mr-2"></i> Update materi gratis</div>
                        <div class="flex items-center"><i class="fas fa-certificate w-6 text-gray-600 text-center mr-2"></i> Sertifikat penyelesaian</div>
                    </div>
                </div>
                
                <div class="p-3 border-t border-gray-100 text-center">
                    <a href="#" class="text-sm font-bold text-gray-800 underline">Bagikan</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection