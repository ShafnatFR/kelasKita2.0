@extends('layouts.app')

@section('title', 'Keranjang Belanja - KelasKu')

@section('content')
<section class="bg-primary py-12 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Keranjang Belanja Anda</h1>
        <p class="text-blue-100 text-lg">{{ count($keranjang) }} kursus siap untuk dipelajari.</p>
    </div>
</section>

<section class="py-16 container mx-auto px-4 sm:px-6 lg:px-8">
    @if(count($keranjang) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <div class="lg:col-span-2 space-y-6">
            @foreach($keranjang as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center transition hover:shadow-md">
                <a href="{{ route('kelas.detail', $item['kelas']['id_kelas']) }}" class="w-full sm:w-48 h-32 flex-shrink-0 mb-4 sm:mb-0 sm:mr-6 relative rounded-xl overflow-hidden group">
                    <img src="{{ $item['kelas']['thumbnail'] ? asset('storage/'.$item['kelas']['thumbnail']) : 'https://via.placeholder.com/200x150' }}" alt="{{ $item['kelas']['nama_kelas'] }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                </a>
                
                <div class="flex-grow">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 leading-snug hover:text-primary transition">
                        <a href="{{ route('kelas.detail', $item['kelas']['id_kelas']) }}">
                            {{ $item['kelas']['nama_kelas'] }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-3 flex items-center">
                        <i class="far fa-user-circle mr-2"></i> Oleh {{ $item['kelas']['mentor']['name'] ?? 'Instruktur' }}
                    </p>
                    <div class="flex items-center text-yellow-400 text-sm mb-4 sm:mb-0">
                        <span class="font-bold text-gray-700 mr-2">4.8</span>
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span class="text-gray-400 ml-2">(210)</span>
                    </div>
                </div>

                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-auto mt-4 sm:mt-0 sm:ml-6">
                    <div class="text-right mb-0 sm:mb-4">
                        <div class="text-2xl font-bold text-primary">Rp {{ number_format($item['kelas']['harga'], 0, ',', '.') }}</div>
                         <div class="text-sm text-gray-400 line-through font-medium">Rp {{ number_format($item['kelas']['harga'] * 1.3, 0, ',', '.') }}</div>
                    </div>
                    <form action="{{ route('keranjang.hapus', $item['id_keranjang']) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center transition bg-red-50 px-3 py-2 rounded-lg hover:bg-red-100">
                            <i class="far fa-trash-alt mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 sticky top-28">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-gray-600 text-lg">
                        <span>Subtotal ({{ count($keranjang) }} kursus)</span>
                        <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 text-lg">
                        <span>Diskon</span>
                        <span class="font-medium text-green-600">-Rp 0</span>
                    </div>
                    <div class="border-t border-gray-100 my-4 pt-4 flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-3xl font-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <button class="btn-primary w-full py-5 rounded-xl font-bold text-xl shadow-md hover:shadow-lg transition flex items-center justify-center mb-4">
                        Checkout Sekarang <i class="fas fa-arrow-right ml-3"></i>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Kode Kupon</label>
                    <div class="flex">
                        <input type="text" placeholder="Masukkan kode" class="flex-grow border border-gray-300 rounded-l-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50">
                        <button class="bg-gray-800 text-white px-6 rounded-r-xl font-bold hover:bg-gray-900 transition">Terapkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100 max-w-3xl mx-auto">
        <img src="https://cdn-icons-png.flaticon.com/512/11329/11329116.png" alt="Keranjang Kosong" class="w-48 mx-auto mb-8 opacity-80">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Keranjang Anda Masih Kosong</h2>
        <p class="text-gray-500 text-lg mb-10 max-w-md mx-auto">Sepertinya Anda belum menambahkan kursus apapun. Yuk, mulai cari skill baru untuk dipelajari!</p>
        <a href="{{ route('home') }}" class="btn-primary px-10 py-4 rounded-full font-bold text-lg shadow-md hover:shadow-lg transition inline-flex items-center">
            <i class="fas fa-search mr-3"></i> Jelajahi Kursus
        </a>
    </div>
    @endif
</section>
@endsection