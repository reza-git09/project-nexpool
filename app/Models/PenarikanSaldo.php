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

    public const STATUS_TERKONFIRMASI = 'terkonfirmasi';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'pool_id',
        'requested_by',
        'bank_name',
        'account_number',
        'amount',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
        'transfer_proof_path',
        'transfer_proof_uploaded_by',
        'transfer_proof_uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
            'transfer_proof_uploaded_at' => 'datetime',
        ];
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'pool_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transferProofUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transfer_proof_uploaded_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PROSES => 'Diproses',
            self::STATUS_BERHASIL => 'Berhasil/Dicairkan',
            self::STATUS_TERKONFIRMASI => 'Terkonfirmasi',
            self::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($this->status),
        };
    }
}
