<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progres Kelas - KelasKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900" x-data="{ openReport: false }">

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <div class="container mx-auto p-6 max-w-6xl">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 font-bold uppercase">PROGRES KELAS</h1>
            <div class="max-w-md mx-auto">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-bold text-blue-600 uppercase">Progres Belajar</span>
                    <span class="text-sm font-bold text-blue-600 font-black">{{ $percentage }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
                    <div id="main-progress-bar" class="bg-blue-600 h-full rounded-full transition-all duration-700" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                @foreach($kelas->materi as $bab)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 font-bold text-xs uppercase tracking-widest text-gray-700">
                        BAB {{ $loop->iteration }}: {{ $bab->nama_materi }}
                    </div>

                    <div class="divide-y divide-gray-100">
                        @php
                            $pdfSub = $bab->subMateri->whereNotNull('id_dokumen')->first();
                            $videoSub = $bab->subMateri->whereNotNull('id_video')->first();
                        @endphp

                        @if($pdfSub)
                        <div class="p-5 hover:bg-blue-50/30 transition">
                            <div class="flex items-center space-x-4">
                                <input type="checkbox" class="progress-checkbox w-6 h-6 text-blue-600 rounded-lg cursor-pointer"
                                       data-sub="{{ $pdfSub->id_sub_materi }}" data-kelas="{{ $kelas->id_kelas }}"
                                       @if(in_array($pdfSub->id_sub_materi, $completedMateri)) checked @endif>
                                <div>
                                    <p class="text-gray-800 font-bold text-sm">{{ $pdfSub->nama_sub_materi }}</p>
                                    <a href="{{ asset('storage/' . $pdfSub->dokumen->path_file) }}" download class="text-[10px] font-black text-blue-500 uppercase mt-1">📄 UNDUH PDF</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($videoSub)
                        <div class="p-5 hover:bg-blue-50/30 transition" x-data="{ openVideo: false }">
                            <div class="flex items-center space-x-4">
                                <input type="checkbox" class="progress-checkbox w-6 h-6 text-blue-600 rounded-lg cursor-pointer"
                                       data-sub="{{ $videoSub->id_sub_materi }}" data-kelas="{{ $kelas->id_kelas }}"
                                       @if(in_array($videoSub->id_sub_materi, $completedMateri)) checked @endif>
                                <div>
                                    <p class="text-gray-800 font-bold text-sm">{{ $videoSub->nama_sub_materi }}</p>
                                    @php
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoSub->video->link_video, $match);
                                        $embed = "https://www.youtube.com/embed/" . ($match[1] ?? '');
                                    @endphp
                                    <button type="button" @click="openVideo = !openVideo" class="text-[10px] font-black text-red-500 uppercase mt-1">
                                        <span x-text="openVideo ? '✖ TUTUP VIDEO' : '▶ LIHAT VIDEO'"></span>
                                    </button>
                                </div>
                            </div>
                            <template x-if="openVideo">
                                <div class="mt-4 bg-black rounded-2xl overflow-hidden aspect-video shadow-lg">
                                    <iframe src="{{ $embed }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </template>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="pt-4 flex justify-start">
                    <button @click="openReport = true" class="flex items-center text-[11px] font-black text-blue-600 uppercase tracking-widest hover:underline decoration-2 underline-offset-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Laporkan Konten Tidak Pantas
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 text-center uppercase text-sm tracking-widest">Ulasan & Rating</h3>
                    
                    <form action="{{ route('review.store', $kelas->id_kelas) }}" method="POST" x-data="{ rating: 0 }">
                        @csrf
                        <input type="hidden" name="bintang" :value="rating">
                        <div class="flex space-x-2 mb-6 justify-center">
                            <template x-for="i in 5">
                                <button type="button" @click="rating = i" class="focus:outline-none transform hover:scale-110 transition">
                                    <svg class="w-8 h-8" :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                        <textarea name="isi_review" rows="4" class="w-full px-4 py-3 rounded-2xl border bg-gray-50 text-xs outline-none mb-4 resize-none" placeholder="Tulis ulasan belajarmu..."></textarea>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 shadow-xl transition uppercase text-[10px] tracking-widest">Simpan Ulasan</button>
                    </form>

                    <div class="mt-10 pt-6 border-t border-gray-100 space-y-4">
                        <h4 class="font-bold text-gray-700 uppercase text-[10px] tracking-widest mb-4 text-center underline underline-offset-4">Daftar Ulasan Murid</h4>
                        @foreach($allReviews as $rev)
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-3 shadow-sm relative">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-[10px] text-blue-600 uppercase">{{ $rev->user->name }}</span>
                                    
                                    @if($rev->id_user == Auth::id())
                                    <form action="{{ route('review.destroy', $rev->id_review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                    @endif
                                </div>
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-2.5 h-2.5 {{ $i <= $rev->bintang ? 'fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-gray-600 text-[11px] italic leading-relaxed">"{{ $rev->isi_review }}"</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="openReport" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl" @click.away="openReport = false">
            <h3 class="text-xl font-bold text-gray-900 mb-2 uppercase tracking-tight">Laporkan Konten</h3>
            <p class="text-xs text-gray-500 mb-6 leading-relaxed">Laporkan video atau PDF yang mengandung konten tidak pantas atau di luar materi kelas.</p>
            
            <form action="{{ route('report.store', $kelas->id_kelas) }}" method="POST">
                @csrf
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Pilih Kategori</label>
                <select name="kategori" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm outline-none mb-4">
                    <option value="Konten Tidak Pantas (SARA/Pornografi)">Konten Tidak Pantas (SARA/Pornografi)</option>
                    <option value="Konten Di Luar Materi">Konten Di Luar Materi</option>
                    <option value="Video/PDF Tidak Bisa Dibuka">Video/PDF Tidak Bisa Dibuka</option>
                    <option value="Lainnya">Lainnya</option>
                </select>

                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Detail Pelanggaran</label>
                <textarea name="keterangan" rows="4" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm outline-none mb-6 resize-none" placeholder="Jelaskan secara detail..."></textarea>

                <div class="flex space-x-3">
                    <button type="button" @click="openReport = false" class="flex-1 px-4 py-4 rounded-2xl bg-gray-100 text-gray-500 font-bold uppercase text-[10px] tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-4 rounded-2xl bg-red-600 text-white font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-red-100">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('main-progress-bar').style.width = "{{ $percentage }}%";
        document.querySelectorAll('.progress-checkbox').forEach(box => {
            box.addEventListener('change', function() {
                fetch("{{ route('progress.toggle') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ id_sub_materi: this.getAttribute('data-sub'), id_kelas: this.getAttribute('data-kelas'), is_completed: this.checked })
                }).then(() => window.location.reload());
            });
        });
    </script>
</body>
</html>