<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - KelasKita</title>
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
                {{-- Menu Sidebar (Singkat) --}}
                <a href="{{ route('mentor.kelas.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium bg-blue-600 text-white rounded-xl">
                    <i class="fas fa-arrow-left w-5 h-5 mr-3 text-blue-200"></i>
                    Kembali ke List
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Edit Kelas</h1>
                    <p class="text-slate-500">Perbarui informasi kelas Anda.</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm max-w-3xl">
                    <div class="p-6 md:p-8">
                        
                        <form action="{{ route('mentor.kelas.update', $kelas->id_kelas) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT') <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kelas</label>
                                <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Kelas (Rp)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">Rp</span>
                                    <input type="number" name="harga" value="{{ old('harga', $kelas->harga) }}" required
                                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 pl-12 pr-4 bg-slate-50">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Kelas</label>
                                <select name="kategori" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                                    @php $cats = ['Programming', 'Desain Grafis', 'Digital Marketing', 'Bisnis & Manajemen', 'Bahasa Asing', 'Pengembangan Diri']; @endphp
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat }}" {{ $kelas->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                                <textarea name="deskripsi" rows="4" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">{{ old('deskripsi', $kelas->description) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Thumbnail Kelas</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="thumbnail" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 relative overflow-hidden group">
                                        
                                        @if($kelas->thumbnail)
                                            <img id="preview" src="{{ asset($kelas->thumbnail) }}" class="w-full h-full object-cover absolute inset-0">
                                            <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white font-medium z-10 transition-all">
                                                Klik untuk ganti gambar
                                            </div>
                                        @else
                                            <div id="placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                                                <p class="text-sm text-slate-500">Upload gambar baru (Opsional)</p>
                                            </div>
                                            <img id="preview" class="hidden w-full h-full object-cover absolute inset-0" />
                                        @endif

                                        <input id="thumbnail" name="thumbnail" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-4">
                                <a href="{{ route('mentor.kelas.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl">Batal</a>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();
            if (input.files && input.files[0]) {
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    const placeholder = document.getElementById('placeholder');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Munculkan gambar
                    
                    if(placeholder) placeholder.classList.add('hidden'); // Sembunyikan teks placeholder
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>