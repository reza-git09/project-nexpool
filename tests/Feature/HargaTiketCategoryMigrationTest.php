<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HargaTiketCategoryMigrationTest extends TestCase
{
    public function test_it_keeps_adult_prices_and_converts_child_only_days_to_the_single_ticket_price(): void
    {
        Schema::create('harga_tiket', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->enum('kategori', ['Dewasa', 'Anak']);
            $table->enum('jenis_hari', ['Weekday', 'Weekend']);
            $table->decimal('harga', 12, 2);
            $table->timestamps();
            $table->unique(['pool_id', 'kategori', 'jenis_hari']);
        });

        DB::table('harga_tiket')->insert([
            [
                'pool_id' => 'pool_id_01',
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => 15000,
            ],
            [
                'pool_id' => 'pool_id_01',
                'kategori' => 'Anak',
                'jenis_hari' => 'Weekday',
                'harga' => 10000,
            ],
            [
                'pool_id' => 'pool_id_01',
                'kategori' => 'Anak',
                'jenis_hari' => 'Weekend',
                'harga' => 12000,
            ],
        ]);

        $migration = require database_path(
            'migrations/2026_11_06_100000_remove_ticket_categories_from_harga_tiket_table.php'
        );
        $migration->up();

        $this->assertFalse(Schema::hasColumn('harga_tiket', 'kategori'));
        $this->assertDatabaseCount('harga_tiket', 2);
        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);
        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekend',
            'harga' => 12000,
        ]);
    }
}
