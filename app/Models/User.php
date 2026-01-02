<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    // Role Constant
    const ROLE_ADMIN = 'admin';
    const ROLE_MENTOR = 'mentor';
    const ROLE_USER = 'student';

    // Status Constant
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BANNED = 'banned';

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'username',
        'email',
        'password',
        'deskripsi',
        'role',
        'deskripsi',
        'foto_profil',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    // ✅ Relasi ke Mentor
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
        return $this->hasOne(Mentor::class, 'id_user', 'id_user');
    }
}