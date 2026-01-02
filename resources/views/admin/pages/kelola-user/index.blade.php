@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">Kelola
                    User</h2>
                <p class="mt-1 text-sm text-slate-500">Manajemen pengguna, role, dan status akun.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.kelola.user.create') }}"
                    class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Tambah
                    User</a>
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.kelola.user') }}" method="GET" class="flex flex-col sm:flex-row gap-4">

                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Username, Email..."
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                    </div>

                    <div>
                        <select name="role" onchange="this.form.submit()"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">- Semua Role -</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>
                                    {{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">- Semua Status -</option>
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}</option>
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
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Username
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Email
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Role
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Status
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($users as $user)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                                    {{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">
                                    {{ $user->username }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    {{ $user->email }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset bg-blue-50 text-blue-700 ring-blue-600/20">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusClass = match ($user->status) {
                                            'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                            'inactive' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                            'banned' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            default => 'bg-slate-50 text-slate-700 ring-slate-600/20',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelola.user.show', $user->id_user) }}"
                                            class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                        @if ($user->status == 'banned')
                                            <form action="{{ route('admin.kelola.user.destroy', $user->id_user) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus
                                                    Permanen</button>
                                            </form>
                                        @elseif ($user->status == 'inactive')
                                            <form action="{{ route('admin.kelola.user.activate', $user->id_user) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin mengaktifkan user ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="text-yellow-600 hover:text-yellow-900">Aktifkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-sm text-slate-500">Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
