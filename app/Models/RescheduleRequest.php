<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    public const STATUS_AWAITING_APPROVAL = 'menunggu_persetujuan';

    public const STATUS_AWAITING_PAYMENT = 'menunggu_pembayaran';

    public const STATUS_REJECTED = 'ditolak';

    public const STATUS_COMPLETED = 'berhasil';

    public const STATUS_EXPIRED = 'kedaluwarsa';

    public const ACTIVE_STATUSES = [
        self::STATUS_AWAITING_APPROVAL,
        self::STATUS_AWAITING_PAYMENT,
    ];

    protected $fillable = [
        'reservasi_id',
        'tanggal_lama',
        'tanggal_baru',
        'selisih_harga',
        'status',
        'alasan_penolakan',
        'expires_at',
        'approved_at',
        'payment_status',
        'payment_order_id',
        'payment_transaction_id',
        'payment_qr_url',
        'payment_expires_at',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_lama' => 'date',
        'tanggal_baru' => 'date',
        'selisih_harga' => 'integer',
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'payment_expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }
}
