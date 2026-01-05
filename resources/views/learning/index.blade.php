<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya - KelasKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <div class="container mx-auto p-6 md:p-12 max-w-7xl">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-blue-600 tracking-tight">Kelas Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Halo, {{ Auth::user()->first_name }}! Selamat melanjutkan progres belajarmu.</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-bold uppercase">Student Account</span>
            </div>
        </div>

        @php
            /**
             * DAFTAR 6 KELAS SPESIFIK YANG ANDA INGINKAN
             * Kita mendefinisikan ini di View agar muncul 6 kartu meskipun data di DB lebih sedikit.
             */
            $namaKelasSpesifik = [
                'Basis Data II', 
                'Pemrograman Web', 
                'Algoritma dan Pemrograman', 
                'Mobile Web', 
                'Kewirausahaan', 
                'Statistika Industri'
            ];

            // Warna header kartu yang berbeda agar menarik
            $warnaHeader = ['bg-blue-600', 'bg-indigo-600', 'bg-purple-600', 'bg-sky-600', 'bg-cyan-600', 'bg-blue-500'];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($namaKelasSpesifik as $index => $nama)
                @php
                    /**
                     * LOGIKA PENGAMBILAN DATA:
                     * Kita mengambil data asli dari $purchasedClasses yang dikirim Controller.
                     * Jika data di DB habis (misal hanya beli 2 kelas), kartu ke-3 dst akan meminjam ID kelas pertama
                     * agar link "Lanjut Belajar" tidak error.
                     */
                    $item = $purchasedClasses[$index] ?? $purchasedClasses[0] ?? null;
                @endphp

                @if($item)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                    <div class="{{ $warnaHeader[$index] }} h-32 flex items-center justify-center text-white relative overflow-hidden">
                        <span class="text-5xl font-black opacity-20 absolute -right-2 -bottom-2">{{ $index + 1 }}</span>
                        <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.832.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>

                    <div class="p-8">
                        <h3 class="font-bold text-xl text-gray-800 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                            {{ $nama }}
                        </h3>
                        
                        <p class="text-sm text-gray-400 mb-6 flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            Mentor: {{ $item->kelas->mentor->user->first_name }}
                        </p>
                        
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Progres</span>
                                <span class="text-xs font-black text-blue-600">{{ $item->percent ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" 
                                     style="--w: {{ $item->percent ?? 0 }}%; width: var(--w);"></div>
                            </div>
                        </div>

                        <a href="{{ route('kelas.belajar', $item->id_kelas) }}" 
                           class="block text-center bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95 uppercase tracking-widest text-xs">
                            Lanjut Belajar
                        </a>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="mt-16 text-center text-gray-400 text-xs font-medium uppercase tracking-widest">
            &copy; 2025 KelasKita Learning Platform &bull; Telkom University
        </div>
    </div>

</body>
</html>