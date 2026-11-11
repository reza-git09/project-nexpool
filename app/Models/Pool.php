<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pool extends Model
{
    use HasFactory;

    protected $table = 'pools';

    protected $fillable = [
        'pool_id',
        'name',
        'registration_code',
    ];

    protected $hidden = [
        'registration_code',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'pool_id', 'pool_id');
    }

    public function adminApplications(): HasMany
    {
        return $this->hasMany(AdminApplication::class, 'pool_id', 'pool_id');
    }
}
