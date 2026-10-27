<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->string('jenis', 20)->default('fasilitas')->after('gambar'); // kolam / fasilitas
            $table->string('kedalaman', 50)->nullable()->after('jenis');        // contoh: 1.2m - 1.5m
            $table->string('usia', 50)->nullable()->after('kedalaman');         // contoh: Dewasa
        });
    }

    public function down(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'kedalaman', 'usia']);
        });
    }
};