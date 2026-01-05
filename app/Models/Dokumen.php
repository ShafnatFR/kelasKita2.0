<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumens';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'file_path', // Ini nanti isinya path file di server (misal: documents/materi1.pdf)
        'tipe_file', // pdf, docx, pptx
    ];
}