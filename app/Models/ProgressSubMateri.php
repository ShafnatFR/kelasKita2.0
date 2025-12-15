<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgressSubMateri extends Model
{
    use HasFactory;
    protected $table = 'progress_sub_materi';
    protected $primaryKey = 'id_progress';

    protected $fillable = ['id_user', 'id_kelas', 'id_sub_materi', 'is_completed'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function subMateri()
    {
        return $this->belongsTo(SubMateri::class, 'id_sub_materi');
    }
}
