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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();

            $table->string('pool_id');
            $table->string('nama_promo');
            $table->text('deskripsi')->nullable();

            $table->enum('jenis_diskon', [
                'Persentase',
                'Nominal'
            ]);

            $table->decimal('nilai_diskon', 12, 2);

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};