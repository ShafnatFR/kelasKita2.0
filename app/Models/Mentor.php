<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $table = 'mentors';
    protected $primaryKey = 'id_mentor'; // PENTING: Karena bukan 'id'
    public $timestamps = true; // Sesuaikan dengan DB Anda

    protected $fillable = [
        'id_user',
        // tambahkan field lain jika ada (misal: bio, keahlian, dll)
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_mentor', 'id_mentor');
    }
}