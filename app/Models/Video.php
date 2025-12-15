<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_video';
    protected $fillable = ['file_path', 'durasi'];
}
