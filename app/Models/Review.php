<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_review';
    protected $fillable = ['id_user', 'id_kelas', 'bintang', 'isi_review'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
