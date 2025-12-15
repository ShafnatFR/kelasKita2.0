<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_dokumen';
    protected $fillable = ['file_path', 'tipe_file'];
}
