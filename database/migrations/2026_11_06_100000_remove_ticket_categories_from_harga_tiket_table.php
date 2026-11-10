<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = collect(DB::select("SHOW INDEX FROM `harga_tiket`"))->pluck('Key_name')->all();

        if (in_array('harga_tiket_pool_id_kategori_jenis_hari_unique', $indexes, true)) {
            Schema::table('harga_tiket', function (Blueprint $table): void {
                $table->dropUnique('harga_tiket_pool_id_kategori_jenis_hari_unique');
            });
        }

        if (Schema::hasColumn('harga_tiket', 'kategori')) {
            $childPriceIds = DB::table('harga_tiket as child_prices')
                ->where('child_prices.kategori', 'Anak')
                ->whereExists(function ($query): void {
                    $query->selectRaw('1')
                        ->from('harga_tiket as adult_prices')
                        ->whereColumn('adult_prices.pool_id', 'child_prices.pool_id')
                        ->whereColumn('adult_prices.jenis_hari', 'child_prices.jenis_hari')
                        ->where('adult_prices.kategori', 'Dewasa');
                })
                ->select('child_prices.id')
                ->pluck('id')
                ->all();

            $childPriceIds = array_values(array_filter($childPriceIds));

            if (! empty($childPriceIds)) {
                DB::table('harga_tiket')
                    ->whereIn('id', $childPriceIds)
                    ->delete();
            }

            DB::table('harga_tiket')
                ->where('kategori', 'Anak')
                ->update(['kategori' => 'Dewasa']);

            Schema::table('harga_tiket', function (Blueprint $table): void {
                $table->dropColumn('kategori');
            });
        }

        $indexesAfter = collect(DB::select("SHOW INDEX FROM `harga_tiket`"))->pluck('Key_name')->all();
        if (! in_array('harga_tiket_pool_id_jenis_hari_unique', $indexesAfter, true)) {
            Schema::table('harga_tiket', function (Blueprint $table): void {
                $table->unique(['pool_id', 'jenis_hari']);
            });
        }
    }

    public function down(): void
    {
        $indexes = collect(DB::select("SHOW INDEX FROM `harga_tiket`"))->pluck('Key_name')->all();
        if (in_array('harga_tiket_pool_id_jenis_hari_unique', $indexes, true)) {
            Schema::table('harga_tiket', function (Blueprint $table): void {
                $table->dropUnique('harga_tiket_pool_id_jenis_hari_unique');
            });
        }

        if (! Schema::hasColumn('harga_tiket', 'kategori')) {
            Schema::table('harga_tiket', function (Blueprint $table): void {
                $table->enum('kategori', ['Dewasa', 'Anak'])->default('Dewasa');
                $table->unique(['pool_id', 'kategori', 'jenis_hari']);
            });
        }
    }
};
