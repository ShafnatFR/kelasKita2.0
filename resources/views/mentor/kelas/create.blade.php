<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kelas Baru - KelasKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-900">

    <div class="flex min-h-screen">
        
        <aside class="w-72 bg-slate-900 text-white flex flex-col shrink-0 hidden lg:flex sticky top-0 h-screen z-50">
            <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-950">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">KelasKita<span class="text-blue-500">.</span></span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ route('mentor.dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-home w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Dashboard
                </a>

                <a href="{{ route('mentor.kelas.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-900/20">
                    <i class="fas fa-chalkboard-teacher w-5 h-5 mr-3 text-blue-200"></i>
                    Kelola Kelas
                </a>

                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-star w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Ulasan Siswa
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Keuangan</p>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-wallet w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Pendapatan
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Akun</p>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-user-circle w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Profil Saya
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-950">
                <div class="flex items-center gap-3 w-full">
                    <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold border border-slate-700">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $user->first_name }}</p>
                        <p class="text-xs text-slate-500 truncate">Mentor</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 lg:hidden sticky top-0 z-40">
                <span class="font-bold text-slate-800">KelasKita.</span>
                <button class="text-slate-500 text-xl"><i class="fas fa-bars"></i></button>
            </header>

            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="mb-6">
                    <a href="{{ route('mentor.kelas.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-blue-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kelas
                    </a>
                    <h1 class="text-2xl font-bold text-slate-900 mt-2">Buat Kelas Baru</h1>
                    <p class="text-slate-500">Isi detail kelas yang ingin Anda ajarkan.</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm max-w-3xl">
                    <div class="p-6 md:p-8">
                        
                        <form action="{{ route('mentor.kelas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div>
                                <label for="nama_kelas" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kelas</label>
                                <input type="text" name="nama_kelas" id="nama_kelas" required placeholder="Contoh: Belajar Laravel 10 untuk Pemula"
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 bg-slate-50">
                                @error('nama_kelas') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="harga" class="block text-sm font-semibold text-slate-700 mb-2">Harga Kelas (Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="harga" id="harga" required placeholder="0" min="0"
                                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 pl-12 pr-4 bg-slate-50">
                                </div>
                                <p class="text-xs text-slate-500 mt-1">* Masukkan 0 jika kelas ini Gratis.</p>
                            </div>
                            <div>
                                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-2">Kategori Kelas</label>
                                <div class="relative">
                                    <select name="kategori" id="kategori" required
                                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 bg-slate-50 appearance-none">
                                        <option value="" disabled selected>Pilih Kategori...</option>
                                        <option value="Programming">Programming</option>
                                        <option value="Desain Grafis">Desain Grafis</option>
                                        <option value="Digital Marketing">Digital Marketing</option>
                                        <option value="Bisnis & Manajemen">Bisnis & Manajemen</option>
                                        <option value="Bahasa Asing">Bahasa Asing</option>
                                        <option value="Pengembangan Diri">Pengembangan Diri</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" required placeholder="Jelaskan apa yang akan dipelajari siswa di kelas ini..."
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 bg-slate-50"></textarea>
                            </div>

                        <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Thumbnail Kelas</label>
                                
                                <div class="flex items-center justify-center w-full">
                                    <label for="thumbnail" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden relative">
                                        
                                        <div id="placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                                            <p class="text-sm text-slate-500"><span class="font-semibold">Klik untuk upload</span> thumbnail</p>
                                            <p class="text-xs text-slate-400 mt-1">PNG, JPG (MAX. 2MB)</p>
                                        </div>

                                        <img id="preview" class="hidden w-full h-full object-cover absolute inset-0" />

                                        <input id="thumbnail" name="thumbnail" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                                    </label>
                                </div>

                                @error('thumbnail') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p> 
                                @enderror
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a href="{{ route('mentor.kelas.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all">
                                    Batal
                                </a>
                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                                    Simpan Kelas
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- SCRIPT JAVASCRIPT UNTUK PREVIEW GAMBAR --}}
    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();
            
            // Jika user memilih file
            if (input.files && input.files[0]) {
                reader.onload = function(e) {
                    // 1. Sembunyikan Placeholder (Ikon Upload)
                    document.getElementById('placeholder').classList.add('hidden');
                    
                    // 2. Tampilkan Gambar Preview
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                // Baca file sebagai URL data
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>
</html>