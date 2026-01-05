<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | KelasKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <nav class="bg-white shadow h-16 flex items-center px-6 mb-8">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">KelasKu</a>
        <span class="mx-4 text-gray-300">|</span>
        <span class="text-lg font-medium text-gray-600">Checkout Aman</span>
    </nav>

    <div class="max-w-6xl mx-auto px-6 pb-12">
        <form action="{{ route('transaksi.bayar') }}" method="POST">
            @csrf
            <input type="hidden" name="id_transaksi" value="{{ $trx['id_transaksi'] }}">

            <div class="lg:flex gap-12">
                <div class="lg:w-2/3">
                    <h2 class="text-2xl font-bold mb-6">Metode Pembayaran</h2>
                    
                    <div class="bg-white border border-gray-300 rounded overflow-hidden">
                        <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center cursor-pointer">
                            <span class="font-bold text-gray-800"><i class="fas fa-lock mr-2"></i> Pilih Metode</span>
                            <span class="text-sm text-gray-500">Koneksi Aman</span>
                        </div>

                        <div class="p-6 space-y-4">
                            @foreach($methods as $mp)
                            <label class="flex items-center p-4 border border-gray-200 rounded cursor-pointer hover:border-purple-600 transition hover:bg-purple-50 group">
                                <input type="radio" name="id_mp" value="{{ $mp['id_mp'] }}" class="w-5 h-5 text-purple-600 focus:ring-purple-500 border-gray-300" required>
                                <div class="ml-4 flex-grow">
                                    <div class="font-bold text-gray-900 group-hover:text-purple-700">{{ $mp['nama_metode'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $mp['nomor_rekening'] }} (a.n {{ $mp['nama_pemilik'] }})</div>
                                </div>
                                <div class="text-gray-400 text-2xl group-hover:text-purple-600">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mt-8 mb-4">Detail Pesanan</h2>
                    <div class="bg-white border border-gray-300 p-6 rounded">
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                            <div>
                                <div class="font-bold">Pembelian Kelas Online</div>
                                <div class="text-sm text-gray-500">Invoice: {{ $trx['kode_invoice'] }}</div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">
                            Dengan menyelesaikan pembelian ini, Anda menyetujui Ketentuan Layanan KelasKu.
                        </p>
                    </div>
                </div>

                <div class="lg:w-1/3 mt-8 lg:mt-0">
                    <div class="bg-white p-6 shadow-sm border border-gray-200 sticky top-4">
                        <h3 class="text-xl font-bold mb-4">Ringkasan</h3>
                        
                        <div class="flex justify-between mb-2 text-gray-600">
                            <span>Harga Asli:</span>
                            <span>Rp {{ number_format($trx['total_harga'] * 1.2, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2 text-gray-600">
                            <span>Diskon:</span>
                            <span>-Rp {{ number_format($trx['total_harga'] * 0.2, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-gray-200 my-4">
                        <div class="flex justify-between mb-6">
                            <span class="font-bold text-lg">Total:</span>
                            <span class="font-bold text-2xl text-gray-900">Rp {{ number_format($trx['total_harga'], 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white font-bold py-4 text-lg hover:bg-purple-700 transition mb-4">
                            Selesaikan Pembayaran
                        </button>
                        
                        <div class="text-center text-xs text-gray-500 mt-4">
                            <i class="fas fa-lock"></i> Transaksi Aman Terenkripsi
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>