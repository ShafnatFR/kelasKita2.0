@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-6rem)]">
        {{-- Main Content (Left) - 65% --}}
        <main class="flex-1 w-full space-y-6 overflow-y-auto pr-2 custom-scrollbar">
            <a href="{{ route('admin.kelola.report') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition mb-2">
                &larr; Kembali ke Daftar Report
            </a>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                {{-- Header Report --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            @php
                                $badgeClasses = match ($report->status) {
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                            <span class="text-slate-400 text-sm font-medium">#{{ $report->id_report }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $report->kategori }}</h1>
                        <p class="text-slate-500 text-sm mt-1">Dilaporkan pada
                            {{ $report->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- User Reporter Info --}}
                <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ substr($report->user->username ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $report->user->username ?? 'Unknown User' }}</h3>
                        <p class="text-sm text-slate-500">{{ $report->user->email ?? '-' }}</p>
                    </div>
                </div>

                {{-- Isi Laporan --}}
                <div class="p-8">
                    <h4 class="text-sm font-bold uppercase text-slate-400 border-b pb-2 mb-4 tracking-widest">Detail Laporan
                    </h4>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                        {!! nl2br(e($report->keterangan)) !!}
                    </div>
                </div>
            </div>

            {{-- Admin Actions --}}
            <div class="bg-slate-900 rounded-xl shadow-lg p-6 text-white border border-slate-800">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-500/20 rounded text-blue-400">⚙️</span>
                    Tindakan Admin
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- UPDATE STATUS --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Update
                                Status</label>
                            <form action="{{ route('admin.kelola.report.update-status', $report->id_report) }}"
                                method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <select name="status"
                                    class="bg-slate-800 border-none rounded-lg flex-1 text-sm text-white focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>
                                        Sedang Diatasi</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved
                                    </option>
                                </select>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-bold transition duration-200">
                                    Simpan
                                </button>
                            </form>
                        </div>

                        {{-- CATATAN ADMIN FORM (Textarea only) --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Catatan
                                Admin</label>
                            <form id="form-catatan"
                                action="{{ route('admin.kelola.report.update-catatan', $report->id_report) }}"
                                method="POST">
                                @csrf @method('PUT')
                                <textarea name="catatan_admin"
                                    class="bg-slate-50 border border-slate-200 rounded-lg w-full text-sm text-slate-600 focus:ring-2 focus:ring-blue-500 px-4 py-3"
                                    rows="4" placeholder="Tulis catatan internal admin di sini...">{{ $report->adminNote->content ?? '' }}</textarea>
                            </form>
                        </div>
                    </div>

                    {{-- ACTIONS FOOTER: DELETE (Left) & SAVE NOTE (Right) --}}
                    <div
                        class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-slate-800 mt-4">
                        {{-- HAPUS (Kiri) --}}
                        <div class="w-full sm:w-auto">
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest sm:hidden">Hapus
                                Laporan</label>
                            <form action="{{ route('admin.kelola.report.destroy', $report->id_report) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus laporan ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full sm:w-auto bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 px-6 py-2 rounded-lg text-sm font-bold transition duration-200">
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>

                        {{-- SIMPAN CATATAN (Kanan) --}}
                        <button type="submit" form="form-catatan"
                            class="w-full sm:w-auto bg-slate-700 hover:bg-slate-600 px-6 py-2 rounded-lg text-sm font-bold uppercase tracking-wider transition duration-200">
                            Simpan Catatan
                        </button>
                    </div>
                </div>
            </div>

        </main>

        {{-- Sidebar (Right) - 35% --}}
        <aside
            class="w-full lg:w-[350px] shrink-0 flex flex-col h-full bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <h2 class="font-bold text-slate-800 flex items-center gap-2">
                    <span>📋</span> Laporan Terbaru Lainnya
                </h2>
            </div>

            <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar">
                @forelse($sidebarReports as $item)
                    <a href="{{ route('admin.kelola.report.show', $item->id_report) }}"
                        class="block p-3 rounded-lg border border-transparent hover:bg-blue-50 hover:border-blue-100 transition group">
                        <div class="flex justify-between items-start mb-1">
                            <span
                                class="text-xs font-bold text-slate-500 group-hover:text-blue-600">{{ $item->kategori }}</span>
                            @php
                                $sidebarBadgeClasses = match ($item->status) {
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="text-[10px] px-1.5 py-0.5 rounded {{ $sidebarBadgeClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-700 line-clamp-2 mb-2">{{ $item->keterangan }}</p>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400">
                            <span>👤 {{ $item->user->username ?? 'User' }}</span>
                            <span>•</span>
                            <span>{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">
                        Tidak ada laporan lain.
                    </div>
                @endforelse
            </div>
        </aside>
    </div>

    <style>
        /* Custom Scrollbar for inner content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection
