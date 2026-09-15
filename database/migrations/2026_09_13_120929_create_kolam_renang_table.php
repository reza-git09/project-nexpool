<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolam_renang', function (Blueprint $table) {
            $table->id();
            $table->string('pool_id')->unique();
            $table->string('nama_kolam');
            $table->text('alamat');
            $table->string('kota')->default('Jember');
            $table->text('deskripsi')->nullable();
            $table->string('maps_url')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kolam_renang');
    }
};