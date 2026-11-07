<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table): void {
            $table->string('status_pembayaran')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable()->index();
            $table->text('midtrans_qr_url')->nullable();
            $table->timestamp('paid_at')->nullable();
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
