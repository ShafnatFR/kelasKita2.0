<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user'; // Custom PK

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'foto_profil',
        'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    // Relasi One-to-One ke Mentor
    public function mentor()
    {
        return $this->hasOne(Mentor::class, 'id_user');
    }

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    // Relasi ke Kelas yang sudah diambil (via Progress)
    public function enrolledClasses()
    {
        // Menggunakan belongsToMany karena ini relasi Many-to-Many
        return $this->belongsToMany(Kelas::class, 'progress_sub_materi', 'id_user', 'id_kelas')
            ->withPivot('is_completed') // Opsional: untuk akses status progress
            ->distinct();
    }
}