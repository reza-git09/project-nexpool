<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KolamRenang extends Model
{
    protected $table = 'kolam_renang';

    protected $fillable = [
        'pool_id',
        'nama_kolam',
        'alamat',
        'kota',
        'deskripsi',
        'maps_url',
        'gambar',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}