<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $primaryKey = 'id_video';

    protected $fillable = [
        'file_path', // Ini isinya URL Youtube (misal: https://www.youtube.com/embed/...)
        'durasi',
    ];
}