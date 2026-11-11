<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KolamRenang;

class Reservasi extends Model
{
    protected $table = 'reservasis';

    protected $fillable = [
        'kode_reservasi',
        'pool_id',
        'nama_pengunjung',
        'no_hp',
        'tanggal_kunjungan',
        'jumlah_dewasa',
        'jumlah_anak',
        'total_harga',
        'status_reservasi',
        'status_pembayaran',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_qr_url',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'jumlah_dewasa' => 'integer',
        'jumlah_anak' => 'integer',
        'total_harga' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi reservasi dengan kolam renang.
     */
    public function kolamRenang()
    {
        return $this->belongsTo(
            KolamRenang::class,
            'pool_id',
            'pool_id'
        );
    }
}
