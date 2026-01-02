@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">Kelola
                    Laporan Transaksi</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar semua transaksi yang terjadi di platform.</p>
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.kelola.laporan') }}" method="GET"
                    class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 lg:grid-cols-6 gap-x-4">

                    {{-- Search --}}
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium leading-6 text-slate-900">Cari
                            Invoice/User</label>
                        <div class="mt-2">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari..."
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    {{-- Filter Metode Pembayaran --}}
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium leading-6 text-slate-900">Metode
                            Pembayaran</label>
                        <div class="mt-2">
                            <select name="metode_pembayaran" id="metode_pembayaran" onchange="this.form.submit()"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option value="">Semua Metode</option>
                                @foreach ($metodePembayaranUnik as $metode)
                                    <option value="{{ $metode }}"
                                        {{ request('metode_pembayaran') == $metode ? 'selected' : '' }}>
                                        {{ $metode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-slate-900">Status</label>
                        <div class="mt-2">
                            <select name="status" id="status" onchange="this.form.submit()"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option value="">Semua Status</option>
                                @foreach ($statusUnik as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Date Filters --}}
                    <div>
                        <label for="start_date" class="block text-sm font-medium leading-6 text-slate-900">Dari</label>
                        <div class="mt-2">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium leading-6 text-slate-900">Sampai</label>
                        <div class="mt-2">
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    {{-- Tombol Cari --}}
                    <div class="lg:col-span-6 flex justify-end mt-2">
                        <button type="submit"
                            class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Terapkan Filter
                        </button>
                    </div>

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
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">User</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Metode
                                Pembayaran</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Total
                                Harga</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Status
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Tanggal
                                Transaksi</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($transaksi as $item)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                                    {{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    {{ $item->user->username ?? '-' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    {{ $item->metodePembayaran->nama_metode ?? '-' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">Rp
                                    {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $item->status == 'paid' || $item->status == 'success' ? 'bg-green-50 text-green-700 ring-green-600/20' : ($item->status == 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 'bg-red-50 text-red-700 ring-red-600/20') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ $item->tgl_transaksi }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    <div class="flex gap-2">
                                        <button class="text-blue-600 hover:text-blue-900">Detail</button>
                                        @if ($item->status == 'cancelled')
                                            <button class="text-red-600 hover:text-red-900">Delete</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-sm text-slate-500">Tidak ada data
                                    transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
