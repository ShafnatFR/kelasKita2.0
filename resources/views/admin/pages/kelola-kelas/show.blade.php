<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kelas->nama_kelas }} - KelasKita</title>
    <!-- No external CSS frameworks -->
    <style>
        /* Minimal reset and layout styles */
        body {
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Layout Container */
        .layout-container {
            display: flex;
            flex: 1;
            height: 100%;
            overflow: hidden;
        }

        /* Left Content (Fixed Width on Desktop) */
        main {
            flex: 0 0 400px;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        /* Right Content (Flexible) */
        aside {
            flex: 1;
            overflow-y: auto;
            background-color: #f9f9f9;
            padding: 20px;
        }

        /* Responsive Layout (Mobile) */
        @media (max-width: 768px) {
            .layout-container {
                flex-direction: column;
            }

            main {
                flex: none;
                /* Auto height based on content or fixed height */
                height: 50%;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ddd;
            }

            aside {
                height: 50%;
                width: 100%;
            }
        }

        /* Components */
        header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        h1,
        h2,
        h3 {
            margin-top: 0;
        }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            color: #333;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        details {
            background: #fff;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        summary {
            padding: 10px 15px;
            cursor: pointer;
            font-weight: bold;
            list-style: none;
            /* Hide default triangle in some browsers */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        summary::after {
            content: '+';
        }

        details[open] summary::after {
            content: '-';
        }

        details[open] {
            border-bottom: 1px solid #ddd;
        }

        .sub-materi-list {
            border-top: 1px solid #eee;
        }

        .btn-sub-materi {
            display: flex;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
            gap: 10px;
        }

        .btn-sub-materi:hover {
            background-color: #f0f4ff;
        }

        .btn-sub-materi:last-child {
            border-bottom: none;
        }

        /* Video/Doc styling in JS content */
        .video-container {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            margin-bottom: 15px;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
        }

        .doc-card {
            background: #f0f4ff;
            border: 1px solid #cce5ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.8em;
            background: #e9ecef;
        }
    </style>
</head>

<body>
    <div class="layout-container">
        <!-- Content Area (Left) -->
        <main>
            <a href="{{ route('admin.kelola.kelas') }}" class="btn-back">&larr; Kembali ke Admin</a>

            <div id="content-body">
                <!-- Content injected here or Default view below -->
                <div id="welcome-state">
                    <h1>{{ $kelas->nama_kelas }}</h1>
                    <p>{{ $kelas->description }}</p>

                    <div style="margin-top: 20px;">
                        <div class="info-box">
                            <h3>Mentor</h3>
                            <p>{{ $kelas->mentor->user->username ?? 'Tanpa Mentor' }}</p>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <div class="info-box" style="flex: 1;">
                                <h3>Kategori</h3>
                                <span class="badge">{{ $kelas->kategori }}</span>
                            </div>
                            <div class="info-box" style="flex: 1;">
                                <h3>Status</h3>
                                <span class="badge">{{ ucfirst($kelas->status_publikasi) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Sidebar Area (Right) -->
        <aside>
            <header>
                <h2>Materi Pembelajaran</h2>
                <small>Silakan pilih materi di bawah</small>
            </header>

            <div class="materi-list">
                @forelse($kelas->materi as $materi)
                    <details open>
                        <summary>{{ $materi->judul_materi }}</summary>
                        <div class="sub-materi-list">
                            @foreach ($materi->subMateri as $sub)
                                <button class="btn-sub-materi"
                                    onclick="showContent('{{ e($sub->judul_sub) }}', '{{ $sub->id_video ? 'video' : 'dokumen' }}', '{{ $sub->video ? $sub->video->url ?? '#' : ($sub->dokumen ? asset('storage/' . $sub->dokumen->file_path) : '#') }}', '{{ e($sub->teks_pembelajaran) }}')">
                                    <span>
                                        @if ($sub->id_video)
                                            &#9654;
                                        @else
                                            &#128196;
                                        @endif
                                    </span>
                                    <span>{{ $sub->judul_sub }}</span>
                                </button>
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p>Belum ada materi</p>
                @endforelse
            </div>
        </aside>
    </div>
</body>

</html>
