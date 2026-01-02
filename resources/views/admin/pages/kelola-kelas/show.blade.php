@extends('admin.layouts.app')

@section('content')
    {{-- Container Utama: Menggunakan flex-row pada layar desktop (lg) --}}
    <div class="flex flex-col lg:flex-row gap-6 items-start">

        {{-- 1. PANEL MATERI (Kiri) --}}
        {{-- w-full pada mobile, lebar tetap (w-80) pada desktop agar tidak terlalu lebar --}}
        <aside class="w-full lg:w-80 shrink-0 sticky top-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Daftar Materi</h3>
                </div>
                <div class="p-4 space-y-4 max-h-[calc(100vh-250px)] overflow-y-auto">
                    @forelse($kelas->materi as $materi)
                        <div>
                            <h4 class="text-xs font-bold text-blue-600 uppercase mb-2">{{ $materi->judul_materi }}</h4>
                            <div class="space-y-1">
                                @foreach ($materi->subMateri as $sub)
                                    <button
                                        onclick="showContent('{{ e($sub->judul_sub) }}', '{{ $sub->id_video ? 'video' : 'dokumen' }}', '{{ $sub->video ? $sub->video->url ?? '#' : ($sub->dokumen ? asset('storage/' . $sub->dokumen->file_path) : '#') }}', '{{ e($sub->teks_pembelajaran) }}')"
                                        class="w-full text-left flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 text-sm text-slate-600 transition group focus:ring-2 focus:ring-blue-200">
                                        <span class="shrink-0 text-slate-400 group-hover:text-blue-500">
                                            @if ($sub->id_video)
                                                🎥
                                            @else
                                                📄
                                            @endif
                                        </span>
                                        <span class="truncate">{{ $sub->judul_sub }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs italic text-center py-4">Belum ada materi.</p>
                    @endforelse
                </div>
            </div>
        </aside>

        {{-- 2. PANEL DETAIL KELAS & KONTROL ADMIN (Kanan/Tengah) --}}
        {{-- flex-1 akan mengambil sisa ruang yang tersedia --}}
        <main class="flex-1 w-full space-y-6">
            {{-- Card Informasi Detail --}}
            <div id="main-content-display">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900 leading-tight">{{ $kelas->nama_kelas }}</h1>
                            <p class="text-blue-600 font-bold mt-1">Rp{{ number_format($kelas->harga, 0, ',', '.') }}</p>
                        </div>
                        <span
                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-widest shrink-0">
                            {{ $kelas->status_publikasi }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Kategori</p>
                            <p class="font-semibold text-slate-700">{{ $kelas->kategori }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Mentor</p>
                            <p class="font-semibold text-slate-700">{{ $kelas->mentor->user->username ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Terakhir Update
                            </p>
                            <p class="font-semibold text-slate-700">{{ $kelas->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <h4 class="text-sm font-bold uppercase text-slate-400 border-b pb-2 mb-3 tracking-widest">Deskripsi
                            Kelas</h4>
                        <p class="text-slate-600 leading-relaxed">{{ $kelas->description }}</p>
                    </div>
                </div>

                {{-- Panel Kontrol Admin (Hanya tampil di Info Kelas) --}}
                <div class="bg-slate-900 rounded-xl shadow-lg p-6 text-white border border-slate-800">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/20 rounded text-blue-400">⚙️</span>
                        Manajemen Admin
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Form Update Status --}}
                        <form action="{{ route('admin.kelola.kelas.update-status', $kelas->id_kelas) }}" method="POST">
                            @csrf @method('PUT')
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Update
                                Visibilitas</label>
                            <div class="flex gap-2">
                                <select name="status_publikasi"
                                    class="bg-slate-800 border-none rounded-lg flex-1 text-sm text-white focus:ring-2 focus:ring-blue-500">
                                    <option value="draft" {{ $kelas->status_publikasi == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                    <option value="published"
                                        {{ $kelas->status_publikasi == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="archived"
                                        {{ $kelas->status_publikasi == 'archived' ? 'selected' : '' }}>
                                        Archived</option>
                                </select>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-bold transition duration-200">Update</button>
                            </div>
                        </form>

                        {{-- Form Catatan Admin --}}
                        <form action="{{ route('admin.kelola.kelas.update-catatan', $kelas->id_kelas) }}" method="POST">
                            @csrf @method('PUT')
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Catatan
                                Verifikasi</label>
                            <textarea name="catatan_admin"
                                class="bg-slate-800 border-none rounded-lg w-full text-sm text-white mb-2 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                                rows="1">{{ $kelas->adminNote->content ?? '' }}</textarea>
                            <button type="submit"
                                class="w-full bg-slate-700 hover:bg-slate-600 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition duration-200">Simpan
                                Catatan</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <script>
        // Store original content to revert back if needed (optional, or just reload)
        // For now, we just swap content. 

        function showContent(title, type, url, description) {
            const container = document.getElementById('main-content-display');
            let contentHtml = '';

            if (type === 'video') {
                // Validasi URL Video
                let videoContent = '';
                if (!url || url === '#' || url === 'http://' || url === 'https://') {
                    videoContent = `
                        <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden shadow-sm mb-6 flex flex-col items-center justify-center text-slate-400 border border-slate-200">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            <span class="font-medium">Video tidak bisa diputar</span>
                            <span class="text-xs mt-1">URL video tidak valid atau kosong</span>
                        </div>
                    `;
                } else {
                    videoContent = `
                        <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-lg mb-6">
                            <iframe class="w-full h-full" src="${url}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    `;
                }

                contentHtml = `
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                             <a href="javascript:location.reload()" class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 inline-block hover:underline">&larr; Kembali ke Info Kelas</a>
                            <h1 class="text-2xl font-bold text-slate-900">${title}</h1>
                        </div>
                        <div class="p-6">
                            ${videoContent}
                            <div class="prose prose-slate max-w-none">
                                <h3 class="text-sm font-bold uppercase text-slate-400 mb-2 tracking-widest">Deskripsi Materi</h3>
                                <div class="text-slate-700 leading-relaxed">${description || 'Tidak ada deskripsi tambahan.'}</div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                contentHtml = `
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <a href="javascript:location.reload()" class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 inline-block hover:underline">&larr; Kembali ke Info Kelas</a>
                            <h1 class="text-2xl font-bold text-slate-900">${title}</h1>
                        </div>
                        <div class="p-6">
                             <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6 flex items-start gap-4">
                                <div class="p-3 bg-white rounded-lg shadow-sm text-3xl">📄</div>
                                <div>
                                    <h3 class="font-bold text-slate-800 mb-1">Dokumen Pembelajaran</h3>
                                    <p class="text-sm text-slate-600 mb-4">Silakan unduh atau baca dokumen materi ini untuk mempelajarinya.</p>
                                    <a href="${url}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Unduh Dokumen
                                    </a>
                                </div>
                            </div>
                            <div class="prose prose-slate max-w-none">
                                <h3 class="text-sm font-bold uppercase text-slate-400 mb-2 tracking-widest">Ringkasan / Teks</h3>
                                <div class="text-slate-700 leading-relaxed">${description || 'Tidak ada teks pembelajaran.'}</div>
                            </div>
                        </div>
                    </div>
                `;
            }

            container.innerHTML = contentHtml;
            // Scroll to top of main area
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
@endsection
