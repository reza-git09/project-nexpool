<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminFeeMigrationTest extends TestCase
{
    public function test_existing_reservations_keep_their_paid_total_and_receive_the_fee_snapshot(): void
    {
        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->decimal('total_harga', 12, 2);
        });
        DB::table('reservasis')->insert([
            'total_harga' => 17000,
        ]);

        $migration = require database_path(
            'migrations/2026_11_27_011657_add_admin_fee_to_reservasis_table.php',
        );
        $migration->up();

        $reservation = DB::table('reservasis')->first();

        $this->assertSame(17000, (int) $reservation->total_harga);
        $this->assertSame(2000, (int) $reservation->admin_fee);
    }
}
