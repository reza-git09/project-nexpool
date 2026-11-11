<?php

namespace App\Models;

use Database\Factories\PenarikanSaldoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenarikanSaldo extends Model
{
    /** @use HasFactory<PenarikanSaldoFactory> */
    use HasFactory;

    public const STATUS_PROSES = 'proses';

    public const STATUS_BERHASIL = 'berhasil';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'pool_id',
        'bank_name',
        'account_number',
        'amount',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'pool_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
