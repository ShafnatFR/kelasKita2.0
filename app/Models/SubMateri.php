<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMateri extends Model
{
    use HasFactory;

    protected $table = 'sub_materi';
    protected $primaryKey = 'id_sub_materi';

    protected $fillable = [
        'id_materi',
        'id_video',
        'id_dokumen',
        'urutan',
        'judul_sub',
        'teks_pembelajaran'
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'id_video');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen');
    }

    // Cek apakah user tertentu sudah menyelesaikan materi ini
    public function isCompletedBy($userId)
    {
        return $this->hasOne(ProgressSubMateri::class, 'id_sub_materi')
            ->where('id_user', $userId)
            ->where('is_completed', true)
            ->exists();
    }
}
