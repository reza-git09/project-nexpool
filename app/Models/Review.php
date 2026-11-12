<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'pool_id',
        'reservation_order_id',
        'nama_pengunjung',
        'rating',
        'komentar',
        'balasan_admin',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
}