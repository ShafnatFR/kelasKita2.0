<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    // Helper: Ubah link youtube biasa jadi embed
    private function convertToEmbedUrl($url)
    {
        // Cek pola youtube biasa (watch?v=ID)
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);

        if (isset($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $url; // Jika tidak cocok, kembalikan apa adanya
    }

    /**
     * POST /api/video
     * Simpan Link YouTube
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url_video' => 'required|url', // Harus format URL valid
            'durasi' => 'nullable|string' // misal "10:05"
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Proses URL jadi Embed
        $finalUrl = $this->convertToEmbedUrl($request->url_video);

        $video = Video::create([
            'file_path' => $finalUrl, // Simpan URL Embed
            'durasi' => $request->durasi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil disimpan',
            'data' => $video // ID ini dipakai untuk create SubMateri
        ], 201);
    }

    /**
     * DELETE /api/video/{id}
     */
    public function destroy($id_video)
    {
        $video = Video::find($id_video);
        if (!$video) return response()->json(['message' => 'Not found'], 404);

        $video->delete();
        return response()->json(['success' => true, 'message' => 'Video dihapus']);
    }
}