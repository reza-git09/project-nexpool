<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardChartTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('pengunjung', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
        });

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_reservasi');
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->string('no_hp');
            $table->date('tanggal_kunjungan');
            $table->unsignedInteger('jumlah_dewasa')->default(0);
            $table->unsignedInteger('jumlah_anak')->default(0);
            $table->string('status_reservasi');
            $table->timestamps();
        });

        Schema::create('promos', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->boolean('status');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
        });
    }

    public function test_dashboard_chart_filters_real_ticket_sales_by_weekday_and_weekend(): void
    {
        $this->travelTo('2026-11-12 12:00:00');

        DB::table('reservasis')->insert([
            $this->reservation('WEEKDAY-RECENT', 'pool_id_01', '2026-11-12', 4, 0, 'Dikonfirmasi'),
            $this->reservation('WEEKDAY-EARLIER', 'pool_id_01', '2026-11-09', 2, 1, 'Selesai'),
            $this->reservation('WEEKEND', 'pool_id_01', '2026-11-07', 1, 2, 'Dikonfirmasi'),
            $this->reservation('PENDING', 'pool_id_01', '2026-11-11', 50, 0, 'Menunggu'),
            $this->reservation('OTHER-POOL', 'pool_id_02', '2026-11-08', 70, 0, 'Dikonfirmasi'),
            $this->reservation('OUTSIDE-RANGE', 'pool_id_01', '2026-10-31', 80, 0, 'Dikonfirmasi'),
        ]);

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
        ])->get('/dashboard');

        $response->assertOk()
            ->assertSee('data-chart-filter="all"', false)
            ->assertSee('data-chart-filter="weekday"', false)
            ->assertSee('data-chart-filter="weekend"', false)
            ->assertSee('data-day-type="weekday"', false)
            ->assertSee('data-day-type="weekend"', false)
            ->assertSee('data-chart-total>10</strong>', false)
            ->assertDontSee('data-ticket-count="50"', false)
            ->assertDontSee('data-ticket-count="70"', false)
            ->assertDontSee('data-ticket-count="80"', false);

        $this->assertMatchesRegularExpression(
            '/data-day-type="weekday"\s+data-ticket-count="4"/',
            $response->getContent(),
        );
        $this->assertMatchesRegularExpression(
            '/data-day-type="weekend"\s+data-ticket-count="3"/',
            $response->getContent(),
        );
    }

    public function test_dashboard_chart_shows_zero_tickets_when_no_sales_exist(): void
    {
        $this->travelTo('2026-11-12 12:00:00');

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
        ])->get('/dashboard');

        $response->assertOk()
            ->assertSee('data-chart-total>0</strong>', false)
            ->assertSee('data-ticket-count="0"', false);
    }

    /**
     * @return array<string, int|string>
     */
    private function reservation(
        string $code,
        string $poolId,
        string $visitDate,
        int $adultTickets,
        int $childTickets,
        string $status,
    ): array {
        return [
            'kode_reservasi' => $code,
            'pool_id' => $poolId,
            'nama_pengunjung' => 'Pengunjung '.$code,
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => $visitDate,
            'jumlah_dewasa' => $adultTickets,
            'jumlah_anak' => $childTickets,
            'status_reservasi' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
