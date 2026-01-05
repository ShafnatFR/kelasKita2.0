<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DokumenController extends Controller
{
    /**
     * POST /api/dokumen
     * Upload dokumen (PDF/Word/PPT)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_dokumen' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240', // Max 10MB
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $file = $request->file('file_dokumen');
            $originalName = $file->getClientOriginalName();
            // Simpan dengan nama unik
            $filename = time() . '_' . preg_replace('/\s+/', '_', $originalName);
            
            // Simpan ke storage/app/public/documents
            $path = $file->storeAs('public/documents', $filename);
            
            // Simpan path relatif ke database
            $dbPath = 'documents/' . $filename;
            $extension = $file->getClientOriginalExtension();

            $dokumen = Dokumen::create([
                'file_path' => $dbPath,
                'tipe_file' => $extension,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data' => $dokumen // ID ini nanti dipakai untuk create SubMateri
            ], 201);

        } catch (\Exception $e) {
            Log::error('Upload dokumen error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    /**
     * DELETE /api/dokumen/{id}
     */
    public function destroy($id_dokumen)
    {
        $dokumen = Dokumen::find($id_dokumen);
        if (!$dokumen) return response()->json(['message' => 'Not found'], 404);

        // Hapus file fisik
        if (Storage::exists('public/' . $dokumen->file_path)) {
            Storage::delete('public/' . $dokumen->file_path);
        }

        $dokumen->delete();
        return response()->json(['success' => true, 'message' => 'Dokumen dihapus']);
    }
}