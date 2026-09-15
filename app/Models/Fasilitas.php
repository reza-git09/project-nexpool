<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';

    protected $fillable = [
        'pool_id',
        'nama_fasilitas',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}