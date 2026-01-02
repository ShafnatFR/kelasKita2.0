<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_report';
    protected $fillable = ['id_user', 'id_kelas', 'kategori', 'keterangan', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function adminNote()
    {
        return $this->morphOne(AdminNote::class, 'notable');
    }
}
