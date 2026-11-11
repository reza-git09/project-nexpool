<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminApplication extends Model
{
    const STATUS_PENDING = 'pending';

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
        'password_hash',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $hidden = [
        'access_code',
        'password_hash',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Label tampilan jabatan
    public function getPositionLabelAttribute(): string
    {
        return match ($this->position) {
            'kasir_tiket' => 'Kasir Tiket',
            'pengelola_fasilitas' => 'Pengelola Fasilitas',
            'staf_admin' => 'Staf Admin',
            default => ucfirst($this->position),
        };
    }

    // Label tampilan status
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'pool_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
