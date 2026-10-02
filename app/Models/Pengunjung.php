<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Pengunjung extends Model
{
    protected $table = 'pengunjung';

    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Otomatis mengenkripsi password
     * sebelum disimpan ke database.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}