<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            if (! Schema::hasColumn('reservasis', 'nama_promo')) {
                $table->string('nama_promo')->nullable()->after('total_harga');
            }

            if (! Schema::hasColumn('reservasis', 'potongan_promo')) {
                $table->unsignedInteger('potongan_promo')->default(0)->after('nama_promo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            if (Schema::hasColumn('reservasis', 'potongan_promo')) {
                $table->dropColumn('potongan_promo');
            }

            if (Schema::hasColumn('reservasis', 'nama_promo')) {
                $table->dropColumn('nama_promo');
            }
        });
    }
};
