<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_mentor',
        'nama_kelas',
        'slug',
        'kategori',
        'harga',
        'thumbnail',
        'description',
        'status_publikasi',
        'catatan_admin'
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_kelas')->orderBy('urutan');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_kelas');
    }
}
