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
            if (!Schema::hasColumn('reservasis', 'nama_promo')) {
                $table->string('nama_promo')->nullable();
            }

            if (!Schema::hasColumn('reservasis', 'potongan_promo')) {
                $table->decimal('potongan_promo', 12, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $columns = array_filter(
                ['nama_promo', 'potongan_promo'],
                fn ($column) => Schema::hasColumn('reservasis', $column)
            );

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};