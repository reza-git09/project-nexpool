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
        if (! Schema::hasColumn('reservasis', 'admin_fee')) {
            Schema::table('reservasis', function (Blueprint $table): void {
                $table->unsignedInteger('admin_fee')->default(2000)->after('total_harga');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('reservasis', 'admin_fee')) {
            Schema::table('reservasis', function (Blueprint $table): void {
                $table->dropColumn('admin_fee');
            });
        }
    }
};
