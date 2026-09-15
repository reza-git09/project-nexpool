<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasis';

    protected $fillable = [
        'kode_reservasi',
        'pool_id',
        'nama_pengunjung',
        'no_hp',
        'tanggal_kunjungan',
        'jumlah_dewasa',
        'jumlah_anak',
        'total_harga',
        'status_reservasi',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'jumlah_dewasa' => 'integer',
        'jumlah_anak' => 'integer',
        'total_harga' => 'decimal:2',
    ];
}