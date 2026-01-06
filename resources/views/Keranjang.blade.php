@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-8 text-slate-800">Keranjang Belanja</h1>

    {{-- Pesan Sukses/Warning --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- LOGIKA UTAMA: Cek apakah session 'cart' ada isinya? --}}
    @if(session('cart') && count(session('cart')) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-2/3 space-y-4">
                @foreach(session('cart') as $id => $details)
                <div class="flex items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <img src="{{ $details['thumbnail'] ? asset('storage/'.$details['thumbnail']) : 'https://via.placeholder.com/150' }}" 
                         class="w-24 h-16 object-cover rounded-lg mr-4 bg-gray-100">
                    
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-800">{{ $details['nama_kelas'] }}</h3>
                        <p class="text-sm text-gray-500">Mentor: {{ $details['mentor'] }}</p>
                    </div>

                    <div class="text-right mr-6">
                        <div class="font-bold text-blue-600 text-lg">Rp {{ number_format($details['harga'], 0, ',', '.') }}</div>
                    </div>

                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 sticky top-24">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Ringkasan</h3>
                    <div class="flex justify-between mb-2 text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-100 my-4"></div>
                <div class="flex justify-between mb-6 text-xl font-bold text-gray-900">
                    <span>Total</span>
                    <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </div>

                {{-- PERHATIKAN: Tombol HARUS dibungkus FORM --}}
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    {{-- Tambahkan type="submit" agar bisa diklik --}}
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg shadow-blue-600/30">
                        Checkout Sekarang
                    </button>
                </form>
                </div>
            </div>
        </div>
    @else
        {{-- TAMPILAN KOSONG (Seperti Screenshot Anda) --}}
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" class="w-24 mx-auto mb-4 opacity-50">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang Anda Masih Kosong</h3>
            <p class="text-gray-500 mb-6">Sepertinya Anda belum menambahkan kursus apapun.</p>
            <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-2 rounded-full font-bold hover:bg-blue-700 transition">
                Jelajahi Kursus
            </a>
        </div>
    @endif
</div>
@endsection