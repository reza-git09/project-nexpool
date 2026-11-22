<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->cascadeOnDelete();
            $table->date('tanggal_lama');
            $table->date('tanggal_baru');
            $table->unsignedBigInteger('selisih_harga')->default(0);
            $table->string('status', 32)->index();
            $table->string('alasan_penolakan', 500)->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamp('approved_at')->nullable();
            $table->string('payment_status', 24)->nullable();
            $table->string('payment_order_id')->nullable()->unique();
            $table->string('payment_transaction_id')->nullable();
            $table->text('payment_qr_url')->nullable();
            $table->timestamp('payment_expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['reservasi_id', 'status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedule_requests');
    }
};
