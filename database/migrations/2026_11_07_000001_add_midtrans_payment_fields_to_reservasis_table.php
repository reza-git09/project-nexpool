<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table): void {
            if (! Schema::hasColumn('reservasis', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('pending');
            }
            if (! Schema::hasColumn('reservasis', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (! Schema::hasColumn('reservasis', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->unique();
            }
            if (! Schema::hasColumn('reservasis', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->index();
            }
            if (! Schema::hasColumn('reservasis', 'midtrans_qr_url')) {
                $table->text('midtrans_qr_url')->nullable();
            }
            if (! Schema::hasColumn('reservasis', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table): void {
            $table->dropUnique(['midtrans_order_id']);
            $table->dropIndex(['midtrans_transaction_id']);
            $table->dropColumn([
                'status_pembayaran',
                'payment_method',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_qr_url',
                'paid_at',
            ]);
        });
    }
};
