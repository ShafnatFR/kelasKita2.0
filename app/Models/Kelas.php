<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


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
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // ✅ Relasi ke Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor', 'id_mentor');
    }

    // ✅ Relasi ke User (via Mentor)
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Mentor::class,
            'id_mentor', // FK di mentors
            'id_user', // FK di users
            'id_mentor', // PK di kelas
            'id_user' // PK di mentors
        );
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kelas) {
            if (empty($kelas->slug)) {
                $kelas->slug = Str::slug($kelas->nama_kelas);
            }
        });

        static::updating(function ($kelas) {
            if ($kelas->isDirty('nama_kelas')) {
                $kelas->slug = Str::slug($kelas->nama_kelas);
            }
        });
    }


    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_kelas');
    }

    public function adminNote()
    {
        return $this->morphOne(AdminNote::class, 'notable');
    }

    

}