<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_applications', function (Blueprint $table): void {
            $table->enum('position', ['kasir_tiket', 'pengelola_fasilitas', 'staf_admin'])
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        if (DB::table('admin_applications')->whereNull('position')->exists()) {
            throw new RuntimeException('Cannot make admin_applications.position required while null values exist.');
        }

        Schema::table('admin_applications', function (Blueprint $table): void {
            $table->enum('position', ['kasir_tiket', 'pengelola_fasilitas', 'staf_admin'])
                ->nullable(false)
                ->change();
        });
    }
};
