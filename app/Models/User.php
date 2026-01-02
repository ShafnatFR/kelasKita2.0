<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role Constant
    const ROLE_ADMIN = 'admin';

    const ROLE_MENTOR = 'mentor';

    const ROLE_USER = 'student';

    // Status Constant
    const STATUS_ACTIVE = 'active';

    const STATUS_INACTIVE = 'inactive';

    const STATUS_BANNED = 'banned';

    protected $table = 'users';

    protected $primaryKey = 'id_user'; // Custom PK

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'deskripsi',
        'foto_profil',
        'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Role Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMentor(): bool
    {
        return $this->role === self::ROLE_MENTOR;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    // Relasi One-to-One ke Mentor
    public function mentor()
    {
        return $this->hasOne(Mentor::class, 'id_user');
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class, 'id_user');
    }

    public function adminNote()
    {
        return $this->morphOne(AdminNote::class, 'notable');
    }

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    /*
    // Relasi ke Kelas yang sudah diambil (via Progress)
    public function enrolledClasses()
    {
        return $this->hasManyThrough(Kelas::class, ProgressSubMateri::class, 'id_user', 'id_kelas', 'id_user', 'id_kelas')->distinct();
    }
    */
}
