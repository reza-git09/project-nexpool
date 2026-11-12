<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminMasterTransaksiController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminMasterTransaksiFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_reservasi');
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->decimal('total_harga', 12, 2);
            $table->string('status_pembayaran');
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('nama_kolam');
        });

        DB::table('kolam_renang')->insert([
            ['pool_id' => 'tiara', 'nama_kolam' => 'Tiara Park'],
            ['pool_id' => 'annasya', 'nama_kolam' => 'Annaysa Waterpark'],
        ]);

        DB::table('reservasis')->insert([
            [
                'kode_reservasi' => 'TIARA-PAID',
                'pool_id' => 'tiara',
                'nama_pengunjung' => 'Pengunjung 1',
                'total_harga' => 50000,
                'status_pembayaran' => 'paid',
                'paid_at' => '2026-11-11 08:00:00',
                'created_at' => '2026-11-10 08:00:00',
                'updated_at' => '2026-11-10 08:00:00',
            ],
            [
                'kode_reservasi' => 'TIARA-PENDING',
                'pool_id' => 'tiara',
                'nama_pengunjung' => 'Pengunjung 2',
                'total_harga' => 30000,
                'status_pembayaran' => 'pending',
                'paid_at' => null,
                'created_at' => '2026-11-11 09:00:00',
                'updated_at' => '2026-11-11 09:00:00',
            ],
            [
                'kode_reservasi' => 'ANNASYA-PAID',
                'pool_id' => 'annasya',
                'nama_pengunjung' => 'Pengunjung 3',
                'total_harga' => 40000,
                'status_pembayaran' => 'paid',
                'paid_at' => '2026-11-11 10:00:00',
                'created_at' => '2026-11-11 10:00:00',
                'updated_at' => '2026-11-11 10:00:00',
            ],
        ]);
    }

    public function test_it_filters_transactions_by_pool(): void
    {
        $transactions = $this->transactionsFor(['pool_id' => 'tiara']);

        $this->assertEqualsCanonicalizing(
            ['TIARA-PAID', 'TIARA-PENDING'],
            $transactions->pluck('kode_reservasi')->all()
        );
    }

    public function test_it_filters_transactions_by_payment_status(): void
    {
        $transactions = $this->transactionsFor([
            'status_pembayaran' => 'paid',
        ]);

        $this->assertEqualsCanonicalizing(
            ['TIARA-PAID', 'ANNASYA-PAID'],
            $transactions->pluck('kode_reservasi')->all()
        );
    }

    public function test_it_filters_transactions_by_the_displayed_transaction_date(): void
    {
        $transactions = $this->transactionsFor(['tanggal' => '2026-11-11']);

        $this->assertEqualsCanonicalizing(
            ['TIARA-PAID', 'TIARA-PENDING', 'ANNASYA-PAID'],
            $transactions->pluck('kode_reservasi')->all()
        );
    }

    private function transactionsFor(array $filters)
    {
        $view = app(AdminMasterTransaksiController::class)->index(
            Request::create('/admin-master/transaksi', 'GET', $filters)
        );

        return $view->getData()['transaksi']->getCollection();
    }
}
