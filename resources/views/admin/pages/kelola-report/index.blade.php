@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">Kelola
                    Report / Isu</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar laporan isu atau masalah yang dikirimkan oleh user.</p>
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.kelola.report') }}" method="GET" class="flex flex-col sm:flex-row gap-4">

                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Masalah..."
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                    </div>

                    <div>
                        <select name="kategori" onchange="this.form.submit()"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">- Semua Kategori -</option>
                            @foreach ($kategoriUnik as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">- Semua Status -</option>
                            @foreach ($statusUnik as $stats)
                                <option value="{{ $stats }}" {{ request('status') == $stats ? 'selected' : '' }}>
                                    {{ ucfirst($stats) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow ring-1 ring-slate-900/5 sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-300">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-slate-900 sm:pl-6">No</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Kategori
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Keterangan
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Status
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($report as $item)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                                    {{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">
                                    {{ $item->kategori }}</td>
                                <td class="px-3 py-4 text-sm text-slate-500 max-w-sm truncate">
                                    {{ Str::limit($item->keterangan, 80, '...') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusClasses = [
                                            'resolved' => 'bg-green-50 text-green-700 ring-green-600/20',
                                            'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            'pending' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'open' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        ];
                                        $currentStatusClass =
                                            $statusClasses[$item->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $currentStatusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelola.report.show', $item->id_report) }}"
                                            class="text-blue-600 hover:text-blue-900">Detail</a>
                                        @if ($item->status == 'resolved')
                                            <form action="{{ route('admin.kelola.report.destroy', $item->id_report) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada report masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
