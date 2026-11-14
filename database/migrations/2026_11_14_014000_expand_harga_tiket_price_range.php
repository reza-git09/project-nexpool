<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_tiket', function (Blueprint $table): void {
            $table->decimal('harga', 12, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('harga_tiket', function (Blueprint $table): void {
            $table->decimal('harga', 6, 2)->change();
        });
    }
};
