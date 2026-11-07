<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_tiket', function (Blueprint $table): void {
            $table->dropUnique('harga_tiket_pool_id_kategori_jenis_hari_unique');
        });

        $childPricesWithAdultPrice = DB::table('harga_tiket as child_prices')
            ->select('child_prices.id')
            ->where('child_prices.kategori', 'Anak')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('harga_tiket as adult_prices')
                    ->whereColumn('adult_prices.pool_id', 'child_prices.pool_id')
                    ->whereColumn('adult_prices.jenis_hari', 'child_prices.jenis_hari')
                    ->where('adult_prices.kategori', 'Dewasa');
            });

        DB::table('harga_tiket')
            ->whereIn('id', $childPricesWithAdultPrice)
            ->delete();

        DB::table('harga_tiket')
            ->where('kategori', 'Anak')
            ->update(['kategori' => 'Dewasa']);

        Schema::table('harga_tiket', function (Blueprint $table): void {
            $table->dropColumn('kategori');
            $table->unique(['pool_id', 'jenis_hari']);
        });
    }

    public function down(): void
    {
        Schema::table('harga_tiket', function (Blueprint $table): void {
            $table->dropUnique('harga_tiket_pool_id_jenis_hari_unique');
            $table->enum('kategori', ['Dewasa', 'Anak'])->default('Dewasa');
            $table->unique(['pool_id', 'kategori', 'jenis_hari']);
        });

        // Child prices removed by up() cannot be reconstructed from a single ticket price.
    }
};
