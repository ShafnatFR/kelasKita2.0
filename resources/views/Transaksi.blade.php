@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Menunggu Pembayaran</h1>
            <p class="text-gray-500">Invoice <span class="font-mono font-bold text-gray-800">#{{ $transaksi->kode_invoice }}</span> berhasil dibuat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- KOLOM KIRI: DETAIL ITEM --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-700">Rincian Pesanan</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach($items as $item)
                        <div class="flex items-center">
                            <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : 'https://via.placeholder.com/80' }}" 
                                 class="w-16 h-12 object-cover rounded mr-3 bg-gray-200">
                            <div class="flex-1">
                                <h4 class="font-bold text-sm text-gray-800 line-clamp-1">{{ $item->nama_kelas }}</h4>
                                <p class="text-xs text-gray-500">Harga Satuan</p>
                            </div>
                            <div class="text-right font-bold text-blue-600 text-sm">
                                Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="border-t border-gray-100 pt-4 mt-4 flex justify-between items-center">
                            <span class="font-bold text-lg text-gray-800">Total Tagihan</span>
                            <span class="font-bold text-2xl text-blue-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: METODE PEMBAYARAN --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-700">Pilih Metode Pembayaran</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Silakan transfer nominal tepat ke salah satu rekening di bawah ini:</p>
                        
                        <form id="paymentForm" action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
                            
                            <div class="space-y-3">
                                @forelse($metodePembayaran as $mp)
                                <label class="block border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer group has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1">
                                            <input type="radio" name="id_metode_pembayaran" value="{{ $mp->id_mp }}" class="mr-3" required>
                                            <div>
                                                <h5 class="font-bold text-gray-800 group-hover:text-blue-700">{{ $mp->nama_metode }}</h5>
                                                <p class="font-mono text-lg font-bold text-gray-900">{{ $mp->nomor_rekening }}</p>
                                                <p class="text-xs text-gray-500">a.n {{ $mp->nama_pemilik }}</p>
                                            </div>
                                        </div>
                                        <i class="fas fa-university text-gray-300 group-hover:text-blue-500 text-2xl"></i>
                                    </div>
                                </label>
                                @empty
                                <div class="text-center py-4 text-red-500 text-sm">
                                    Belum ada metode pembayaran yang tersedia. Hubungi Admin.
                                </div>
                                @endforelse
                            </div>

                            {{-- Upload Bukti Bayar (Opsional) --}}
                            @if($metodePembayaran->count() > 0)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran (Opsional)</label>
                                <input type="file" name="bukti_bayar" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 2MB</p>
                            </div>

                            {{-- Tombol Konfirmasi --}}
                            <div class="mt-6">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition">
                                    <i class="fas fa-upload mr-2"></i> Konfirmasi Pembayaran
                                </button>
                                <a href="{{ route('home') }}" class="block text-center text-sm text-gray-500 mt-3 hover:underline">Bayar Nanti</a>
                            </div>
                            @else
                            <div class="mt-6">
                                <a href="{{ route('home') }}" class="block text-center w-full bg-gray-400 text-white font-bold py-3 rounded-lg shadow">
                                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                                </a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection