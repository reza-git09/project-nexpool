<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    protected $table = 'pools';

    protected $fillable = [
        'pool_id',
        'name',
        'registration_code',
    ];

    protected $hidden = [
        'registration_code',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'pool_id', 'pool_id');
    }
}
