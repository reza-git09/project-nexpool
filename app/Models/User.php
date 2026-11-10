<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // tambah ini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // tambah HasApiTokens

    protected $fillable = [
        'name',
        'username',
        'pool_id',
        'email',
        'password',
        'role',
        'must_change_password',
        'position',
        'phone',
        'full_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function isAdminMaster(): bool
    {
        return $this->role === 'admin_master';
    }

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'pool_id');
    }
}