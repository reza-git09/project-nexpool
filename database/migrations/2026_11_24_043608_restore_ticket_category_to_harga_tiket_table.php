<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->enum('kategori', ['Dewasa', 'Anak'])->default('Dewasa')->after('pool_id');
        });

        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->unique(['pool_id', 'kategori', 'jenis_hari']);
        });

        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->dropUnique(['pool_id', 'jenis_hari']);
        });
    }

    public function down(): void
    {
        $childPriceIds = DB::table('harga_tiket as child_prices')
            ->where('child_prices.kategori', 'Anak')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('harga_tiket as adult_prices')
                    ->whereColumn('adult_prices.pool_id', 'child_prices.pool_id')
                    ->whereColumn('adult_prices.jenis_hari', 'child_prices.jenis_hari')
                    ->where('adult_prices.kategori', 'Dewasa');
            })
            ->pluck('child_prices.id')
            ->all();

        if ($childPriceIds !== []) {
            DB::table('harga_tiket')->whereIn('id', $childPriceIds)->delete();
        }

        DB::table('harga_tiket')
            ->where('kategori', 'Anak')
            ->update(['kategori' => 'Dewasa']);

        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->unique(['pool_id', 'jenis_hari']);
        });

        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->dropUnique(['pool_id', 'kategori', 'jenis_hari']);
        });

        Schema::table('harga_tiket', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
