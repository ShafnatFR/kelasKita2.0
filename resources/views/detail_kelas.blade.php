@extends('layouts.app')

@section('title', $kelas['nama_kelas'] . ' - Detail Kelas')

@section('content')
<section class="bg-primary py-16 relative">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex text-blue-200 text-sm font-medium space-x-2 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="#" class="hover:text-white transition">Detail Kursus</a>
            <span>/</span>
            <span class="text-white truncate max-w-xs">{{ $kelas['nama_kelas'] }}</span>
        </nav>

        <div class="lg:w-2/3 pr-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">{{ $kelas['nama_kelas'] }}</h1>
            <p class="text-blue-100 text-lg mb-6 leading-relaxed">{{ Str::limit($kelas['description'], 150) }}</p>
            
            <div class="flex flex-wrap items-center gap-6 text-white text-sm font-medium">
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas['mentor']['name'] ?? 'M') }}&background=random" class="w-8 h-8 rounded-full border-2 border-white mr-3">
                    <span>Oleh {{ $kelas['mentor']['name'] ?? 'Instruktur' }}</span>
                </div>
                <div class="flex items-center text-yellow-400">
                    <span class="font-bold text-lg mr-2">4.8</span>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-blue-200 ml-2">(450 Ulasan)</span>
                </div>
                 <div class="flex items-center text-blue-200">
                    <i class="fas fa-user-graduate mr-2"></i> 1.200 Peserta
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="far fa-file-alt text-primary mr-3 bg-blue-100 p-2 rounded-lg"></i> Tentang Kursus Ini
                </h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($kelas['description'])) !!}
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list-ul text-primary mr-3 bg-blue-100 p-2 rounded-lg"></i> Kurikulum
                    </h2>
                    <span class="text-sm text-gray-500 font-medium">{{ count($materi) }} Materi • Total 5 Jam</span>
                </div>

                <div class="space-y-3">
                    @forelse($materi as $index => $m)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition cursor-pointer group">
                        <div class="flex items-center">
                            <span class="w-10 h-10 rounded-full bg-white text-primary border border-blue-100 flex items-center justify-center font-bold mr-4 shadow-sm group-hover:bg-primary group-hover:text-white transition">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <h3 class="font-semibold text-gray-700 group-hover:text-primary transition">{{ $m['judul_materi'] }}</h3>
                        </div>
                        <div class="flex items-center text-gray-400 text-sm">
                            <span class="mr-4 hidden sm:block">15:00</span>
                            <i class="fas fa-play-circle text-lg group-hover:text-primary transition"></i>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 italic bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        Materi belum tersedia untuk kursus ini.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden sticky top-28">
                <div class="relative group cursor-pointer">
                    <img src="{{ $kelas['thumbnail'] ? asset('storage/'.$kelas['thumbnail']) : 'https://via.placeholder.com/600x400' }}" class="w-full h-56 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                         <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg pl-1 scale-90 group-hover:scale-105 transition">
                            <i class="fas fa-play text-primary text-2xl"></i>
                         </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <span class="text-white font-semibold text-sm"><i class="fas fa-play mr-2"></i> Pratinjau Kursus</span>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-bold text-gray-900 mr-3">Rp {{ number_format($kelas['harga'], 0, ',', '.') }}</span>
                        <span class="text-gray-400 line-through mb-1 font-medium">Rp {{ number_format($kelas['harga'] * 1.3, 0, ',', '.') }}</span> </div>

                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="grid gap-4">
                        @csrf
                        <input type="hidden" name="id_kelas" value="{{ $kelas['id_kelas'] }}">
                        
                        <button type="submit" class="btn-primary w-full py-4 rounded-xl font-bold text-lg shadow-md hover:shadow-lg transition flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                        </button>
                        <button type="button" class="btn-outline-primary w-full py-4 rounded-xl font-bold text-lg transition flex items-center justify-center">
                            Beli Sekarang
                        </button>
                    </form>
                    
                    <p class="text-center text-gray-500 text-sm mt-4 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-primary mr-2"></i> Jaminan 30 Hari Uang Kembali
                    </p>

                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h4 class="font-bold text-gray-800 mb-4">Kursus ini mencakup:</h4>
                        <ul class="space-y-3 text-sm text-gray-600 font-medium">
                            <li class="flex items-center"><i class="fas fa-video text-primary w-8 text-center mr-2"></i> 15 jam video on-demand</li>
                            <li class="flex items-center"><i class="fas fa-file-alt text-primary w-8 text-center mr-2"></i> 5 artikel pendukung</li>
                            <li class="flex items-center"><i class="fas fa-download text-primary w-8 text-center mr-2"></i> 10 sumber daya unduhan</li>
                            <li class="flex items-center"><i class="fas fa-infinity text-primary w-8 text-center mr-2"></i> Akses seumur hidup penuh</li>
                            <li class="flex items-center"><i class="fas fa-mobile-alt text-primary w-8 text-center mr-2"></i> Akses di ponsel dan TV</li>
                            <li class="flex items-center"><i class="fas fa-trophy text-primary w-8 text-center mr-2"></i> Sertifikat penyelesaian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection