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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();

            $table->string('kode_reservasi')->unique();
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->string('no_hp');
            $table->date('tanggal_kunjungan');

            $table->integer('jumlah_dewasa')->default(0);
            $table->integer('jumlah_anak')->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);

            $table->enum('status_reservasi', [
                'Menunggu',
                'Dikonfirmasi',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};