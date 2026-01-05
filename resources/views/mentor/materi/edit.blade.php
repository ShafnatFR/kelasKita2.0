<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bab - {{ $kelas->nama_kelas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-900">

    <div class="flex min-h-screen">
        <main class="flex-1 flex flex-col bg-slate-50 min-w-0 overflow-hidden">
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                <div class="max-w-2xl mx-auto">
                    
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Judul Bab</h1>
                        <p class="text-slate-500">Ubah nama bab atau urutannya.</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <form action="{{ route('mentor.materi.update', ['id_kelas' => $kelas->id_kelas, 'id_materi' => $materi->id_materi]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Bab</label>
                                <input type="text" name="judul_materi" value="{{ old('judul_materi', $materi->judul_materi) }}" required 
                                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Urut</label>
                                <input type="number" name="urutan" value="{{ old('urutan', $materi->urutan) }}" required min="1"
                                    class="w-24 rounded-xl border-slate-300 focus:border-blue-500 py-3 px-4 bg-slate-50">
                                <p class="text-xs text-slate-500 mt-1">Ubah angka ini untuk mengatur urutan bab.</p>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                <a href="{{ route('mentor.materi.index', $kelas->id_kelas) }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl">Batal</a>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>