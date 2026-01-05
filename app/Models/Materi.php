<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    
    protected $fillable = [
        'id_kelas',
        'urutan',
        'judul_materi',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Sub Materi
    public function subMateri()
    {
        // hasMany(Model, Foreign Key, Local Key)
        return $this->hasMany(SubMateri::class, 'id_materi', 'id_materi');
    }
}