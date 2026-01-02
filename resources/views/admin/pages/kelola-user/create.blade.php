@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">Tambah User
                </h2>
                <p class="mt-1 text-sm text-slate-500">Form untuk menambahkan user baru ke sistem.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.kelola.user') }}"
                    class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <form action="{{ route('admin.kelola.user.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    {{-- First Name --}}
                    <div>
                        <label for="first_name" class="block text-sm font-medium leading-6 text-slate-900">Nama
                            Depan</label>
                        <div class="mt-2">
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('first_name') ring-red-300 focus:ring-red-600 @enderror"
                                required>
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label for="last_name" class="block text-sm font-medium leading-6 text-slate-900">Nama
                            Belakang</label>
                        <div class="mt-2">
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('last_name') ring-red-300 focus:ring-red-600 @enderror"
                                required>
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 text-slate-900">Username</label>
                        <div class="mt-2">
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('username') ring-red-300 focus:ring-red-600 @enderror"
                                required>
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('email') ring-red-300 focus:ring-red-600 @enderror"
                                required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium leading-6 text-slate-900">Role</label>
                        <div class="mt-2">
                            <select id="role" name="role"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('role') ring-red-300 focus:ring-red-600 @enderror">
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="mentor" {{ old('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-slate-500">Note: Jika memilih Mentor, status mentor akan diset ke
                                'Pending'.</p>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-slate-900">Status Akun</label>
                        <div class="mt-2">
                            <select id="status" name="status"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('status') ring-red-300 focus:ring-red-600 @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Password</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 @error('password') ring-red-300 focus:ring-red-600 @enderror"
                                required>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit"
                        class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Simpan
                        User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
