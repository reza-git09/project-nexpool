<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaTiket extends Model
{
    protected $table = 'harga_tiket';

    protected $fillable = [
        'pool_id',
        'kategori',
        'jenis_hari',
        'harga',
    ];
}