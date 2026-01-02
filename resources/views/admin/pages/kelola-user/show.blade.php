@extends('admin.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.kelola.user') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition mb-6">
            &larr; Kembali ke Daftar User
        </a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Profile Card (Left) --}}
            <div class="col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6 text-center">
                    <div
                        class="w-24 h-24 bg-blue-100 rounded-full mx-auto flex items-center justify-center text-blue-600 font-bold text-3xl mb-4">
                        {{ substr($user->username, 0, 1) }}
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $user->username }}</h2>
                    <p class="text-sm text-slate-500 mb-4">{{ $user->email }}</p>

                    <div class="flex justify-center gap-2 mb-6">
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold ring-1 ring-inset bg-blue-50 text-blue-700 ring-blue-600/20">
                            {{ ucfirst($user->role) }}
                        </span>
                        @php
                            $statusClass = match ($user->status) {
                                'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                'inactive' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                'banned' => 'bg-red-50 text-red-700 ring-red-600/20',
                                default => 'bg-slate-50 text-slate-700 ring-slate-600/20',
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ring-1 ring-inset {{ $statusClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 text-left">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">Member Sejak</p>
                        <p class="text-sm text-slate-700">{{ $user->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Details & Actions (Right) --}}
            <div class="col-span-1 md:col-span-2 space-y-6">
                {{-- Deskripsi --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-bold uppercase text-slate-400 tracking-wider mb-4 border-b pb-2">Biodata /
                        Deskripsi</h3>
                    @if ($user->deskripsi)
                        <p class="text-slate-700 leading-relaxed">{{ $user->deskripsi }}</p>
                    @else
                        <p class="text-slate-400 italic text-sm">Belum ada deskripsi.</p>
                    @endif

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Nama Depan</p>
                            <p class="text-slate-700 font-medium">{{ $user->first_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Nama Belakang</p>
                            <p class="text-slate-700 font-medium">{{ $user->last_name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Action Panel --}}
                <div class="bg-slate-900 rounded-xl shadow-lg p-6 text-white border border-slate-800">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/20 rounded text-blue-400">⚙️</span>
                        Tindakan Admin
                    </h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- UPDATE STATUS --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Update
                                    Status</label>
                                <form action="{{ route('admin.kelola.user.update-status', $user->id_user) }}"
                                    method="POST" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <select name="status"
                                        class="bg-slate-800 border-none rounded-lg flex-1 text-sm text-white focus:ring-2 focus:ring-blue-500">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned
                                        </option>
                                    </select>
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-bold transition duration-200">
                                        Simpan
                                    </button>
                                </form>
                            </div>

                            {{-- CATATAN ADMIN FORM --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest">Catatan
                                    Admin</label>
                                <form id="form-catatan"
                                    action="{{ route('admin.kelola.user.update-catatan', $user->id_user) }}"
                                    method="POST">
                                    @csrf @method('PUT')
                                    <textarea name="catatan_admin"
                                        class="bg-slate-800 border-none rounded-lg w-full text-sm text-white focus:ring-2 focus:ring-blue-500 px-3 py-2"
                                        rows="3" placeholder="Tulis catatan untuk user ini...">{{ $user->adminNote->content ?? '' }}</textarea>
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
                                    User</label>
                                <form action="{{ route('admin.kelola.user.destroy', $user->id_user) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini secara permanen?')">
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
            </div>
        </div>
    </div>
@endsection
