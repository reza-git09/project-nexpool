<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminMasterController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminMasterDashboardRevenueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->decimal('total_harga', 12, 2);
            $table->unsignedInteger('admin_fee');
            $table->string('status_pembayaran');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('nama_kolam');
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('role')->nullable();
        });

        Schema::create('harga_tiket', function (Blueprint $table): void {
            $table->id();
        });

        Schema::create('admin_applications', function (Blueprint $table): void {
            $table->id();
            $table->string('status');
        });

        Schema::create('penarikan_saldos', function (Blueprint $table): void {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->string('status');
        });

        DB::table('kolam_renang')->insert([
            'pool_id' => 'pool_01',
            'nama_kolam' => 'Tiara Park',
        ]);
    }

    public function test_dashboard_hides_platform_revenue_and_calculates_pool_balance(): void
    {
        $this->createReservation('paid', 17000, 2000, '2026-11-27 08:00:00');
        DB::table('penarikan_saldos')->insert([
            'amount' => 5000,
            'status' => 'terkonfirmasi',
        ]);

        $view = app(AdminMasterController::class)->index();
        $data = $view->getData();

        $this->assertArrayNotHasKey('pendapatanPlatform', $data);
        $this->assertSame(10000.0, $data['totalPendapatan']);
        $this->assertStringNotContainsString('Pendapatan Platform', $view->render());
    }

    public function test_dashboard_chart_reports_ticket_revenue_without_the_admin_fee(): void
    {
        $this->travelTo('2026-11-27 12:00:00');
        $this->createReservation('paid', 17000, 2000, '2026-11-27 08:00:00');
        $this->createReservation('pending', 50000, 2000, null);

        $response = app(AdminMasterController::class)->grafikPendapatan(
            Request::create('/admin-master/dashboard/grafik-pendapatan', 'GET', [
                'periode' => '7_hari',
            ])
        );
        $data = $response->getData(true);

        $this->assertSame(15000.0, (float) $data['total']);
        $this->assertContains(15000, $data['values']);
    }

    private function createReservation(
        string $status,
        int $total,
        int $adminFee,
        ?string $paidAt,
    ): void {
        DB::table('reservasis')->insert([
            'pool_id' => 'pool_01',
            'total_harga' => $total,
            'admin_fee' => $adminFee,
            'status_pembayaran' => $status,
            'paid_at' => $paidAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
