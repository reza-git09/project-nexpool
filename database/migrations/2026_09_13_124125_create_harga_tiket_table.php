<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_tiket', function (Blueprint $table) {
            $table->id();
            $table->string('pool_id');
            $table->enum('kategori', ['Dewasa', 'Anak']);
            $table->enum('jenis_hari', ['Weekday', 'Weekend']);
            $table->decimal('harga', 6, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_tiket');
    }
};