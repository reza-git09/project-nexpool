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
        Schema::table('reservasis', function (Blueprint $table) {
            $table->string('nama_promo')->nullable();
            $table->decimal('potongan_promo', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn(['nama_promo', 'potongan_promo']);
        });
    }
};
