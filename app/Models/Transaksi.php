<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'id_mp',
        'kode_invoice',
        'total_harga',
        'status',
        'bukti_bayar',
        'tgl_transaksi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_mp');
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi');
    }
}
