<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApplication extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'phone',
        'position',
        'pool_id',
        'access_code',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Label tampilan jabatan
    public function getPositionLabelAttribute(): string
    {
        return match ($this->position) {
            'kasir_tiket'          => 'Kasir Tiket',
            'pengelola_fasilitas'  => 'Pengelola Fasilitas',
            'staf_admin'           => 'Staf Admin',
            default                => ucfirst($this->position),
        };
    }

    // Label tampilan status
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'pool_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

