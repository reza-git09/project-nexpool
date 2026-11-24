<?php

namespace Tests\Feature;

use App\Models\Promo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PromoReservationApiTest extends TestCase
{
    public function test_promo_status_is_inactive_after_its_end_date(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 11, 24)->startOfDay());

        try {
            $expiredPromo = new Promo([
                'tanggal_selesai' => '2026-11-23',
                'status' => true,
            ]);
            $promoEndingToday = new Promo([
                'tanggal_selesai' => '2026-11-24',
                'status' => true,
            ]);

            $this->assertFalse($expiredPromo->status);
            $this->assertTrue($promoEndingToday->status);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_promo_list_only_returns_pool_promos_valid_for_the_requested_visit_date(): void
    {
        $this->createPromoSchema();

        DB::table('promos')->insert([
            $this->promo('Promo Kolam 1', 'pool_id_01', '2026-11-20', '2026-11-22'),
            $this->promo('Promo Kolam 2', 'pool_id_02', '2026-11-20', '2026-11-22'),
            $this->promo('Promo Nonaktif', 'pool_id_01', '2026-11-20', '2026-11-22', false),
            $this->promo('Promo Kedaluwarsa', 'pool_id_01', '2026-11-01', '2026-11-10'),
        ]);

        $this->getJson('/api/promo?pool_id=pool_id_01&tanggal_kunjungan=2026-11-21')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Promo Kolam 1')
            ->assertJsonPath('data.0.pool_id', 'pool_id_01');
    }

    public function test_reservation_applies_percentage_promo_and_saves_the_discount_snapshot(): void
    {
        $this->createPromoSchema();
        $promoId = DB::table('promos')->insertGetId(
            $this->promo('Diskon Weekend', 'pool_id_01', '2026-11-17', '2026-11-30', true, 'Persentase', 20),
        );
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'promo_id' => $promoId,
            'total_harga' => 26000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.nama_promo', 'Diskon Weekend')
            ->assertJsonPath('data.potongan_promo', '6000.00')
            ->assertJsonPath('data.subtotal_harga', 32000)
            ->assertJsonPath('data.total_harga', '26000.00');

        $this->assertDatabaseHas('reservasis', [
            'pool_id' => 'pool_id_01',
            'nama_promo' => 'Diskon Weekend',
            'potongan_promo' => 6000,
            'total_harga' => 26000,
        ]);
    }

    public function test_reservation_uses_the_weekend_price_for_a_weekend_visit(): void
    {
        $this->createPromoSchema();
        $promoId = DB::table('promos')->insertGetId(
            $this->promo('Diskon Weekend', 'pool_id_01', '2026-11-20', '2026-11-22', true, 'Persentase', 10),
        );
        $this->addPrice('pool_id_01', 'Weekend', 20000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'tanggal_kunjungan' => '2026-11-21',
            'promo_id' => $promoId,
            'total_harga' => 38000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.potongan_promo', '4000.00')
            ->assertJsonPath('data.total_harga', '38000.00');
    }

    public function test_reservation_uses_adult_price_when_child_price_exists_for_the_same_day(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_01', 'Weekday', 15000, 'Dewasa');
        $this->addPrice('pool_id_01', 'Weekday', 5000, 'Anak');

        $this->postJson('/api/reservasi', $this->reservationPayload())
            ->assertCreated()
            ->assertJsonPath('data.subtotal_harga', 32000)
            ->assertJsonPath('data.total_harga', '32000.00');
    }

    public function test_reservation_charges_children_at_the_configured_child_price(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_02', 'Weekday', 15000);
        $this->addPrice('pool_id_02', 'Weekday', 7500, 'Anak');

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'pool_id' => 'pool_id_02',
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 2,
            'total_harga' => 32000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.subtotal_harga', 32000)
            ->assertJsonPath('data.total_harga', '32000.00');
    }

    public function test_tiara_children_are_charged_at_the_configured_child_price(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_01', 'Weekday', 15000);
        $this->addPrice('pool_id_01', 'Weekday', 7500, 'Anak');

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 2,
            'total_harga' => 32000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.subtotal_harga', 32000)
            ->assertJsonPath('data.total_harga', '32000.00');

        $this->assertDatabaseHas('reservasis', [
            'pool_id' => 'pool_id_01',
            'jumlah_anak' => 2,
            'total_harga' => 32000,
        ]);
    }

    public function test_tiara_children_are_free_when_no_child_price_is_configured(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 2,
            'total_harga' => 17000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.subtotal_harga', 17000)
            ->assertJsonPath('data.total_harga', '17000.00');
    }

    public function test_reservation_rejects_children_when_a_child_price_is_missing_for_the_day(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_02', 'Weekend', 20000);
        $this->addPrice('pool_id_02', 'Weekday', 7500, 'Anak');

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'pool_id' => 'pool_id_02',
            'tanggal_kunjungan' => '2026-11-21',
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 1,
            'total_harga' => 22000,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                'Harga tiket anak untuk kolam dan tanggal tersebut belum tersedia.',
            );

        $this->assertDatabaseCount('reservasis', 0);
    }

    public function test_reservation_without_a_promo_keeps_the_regular_ticket_total(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload())
            ->assertCreated()
            ->assertJsonPath('data.nama_promo', null)
            ->assertJsonPath('data.potongan_promo', '0.00')
            ->assertJsonPath('data.total_harga', '32000.00');

        $this->assertDatabaseHas('reservasis', [
            'nama_promo' => null,
            'potongan_promo' => 0,
            'total_harga' => 32000,
        ]);
    }

    public function test_nominal_promo_is_capped_at_ticket_subtotal_without_discounting_the_admin_fee(): void
    {
        $this->createPromoSchema();
        $promoId = DB::table('promos')->insertGetId(
            $this->promo('Potongan Besar', 'pool_id_01', '2026-11-17', '2026-11-30', true, 'Nominal', 25000),
        );
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'jumlah_dewasa' => 1,
            'promo_id' => $promoId,
            'total_harga' => 2000,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.potongan_promo', '15000.00')
            ->assertJsonPath('data.total_harga', '2000.00');
    }

    public function test_reservation_rejects_a_promo_from_a_different_pool_without_saving(): void
    {
        $this->createPromoSchema();
        $promoId = DB::table('promos')->insertGetId(
            $this->promo('Promo Kolam Lain', 'pool_id_02', '2026-11-17', '2026-11-30'),
        );
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'promo_id' => $promoId,
            'total_harga' => 32000,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Promo tidak aktif atau tidak berlaku untuk kolam dan tanggal kunjungan ini.');

        $this->assertDatabaseCount('reservasis', 0);
    }

    public function test_reservation_rejects_a_client_total_that_does_not_match_server_pricing(): void
    {
        $this->createPromoSchema();
        $this->addPrice('pool_id_01', 'Weekday', 15000);

        $this->postJson('/api/reservasi', $this->reservationPayload([
            'total_harga' => 1,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Total pembayaran berubah. Periksa kembali harga tiket dan promo.');

        $this->assertDatabaseCount('reservasis', 0);
    }

    private function createPromoSchema(): void
    {
        Schema::dropIfExists('reservasis');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('harga_tiket');

        Schema::create('harga_tiket', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('kategori')->default('Dewasa');
            $table->string('jenis_hari');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });

        Schema::create('promos', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('nama_promo');
            $table->text('deskripsi')->nullable();
            $table->string('jenis_diskon');
            $table->decimal('nilai_diskon', 12, 2);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_reservasi')->unique();
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->string('no_hp');
            $table->date('tanggal_kunjungan');
            $table->unsignedInteger('jumlah_dewasa')->default(0);
            $table->unsignedInteger('jumlah_anak')->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->string('nama_promo')->nullable();
            $table->decimal('potongan_promo', 12, 2)->default(0);
            $table->string('status_reservasi')->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * @return array{
     *     pool_id: string,
     *     nama_promo: string,
     *     deskripsi: string,
     *     jenis_diskon: string,
     *     nilai_diskon: float,
     *     tanggal_mulai: string,
     *     tanggal_selesai: string,
     *     status: bool,
     *     created_at: Carbon,
     *     updated_at: Carbon
     * }
     */
    private function promo(
        string $name,
        string $poolId,
        string $startDate,
        string $endDate,
        bool $active = true,
        string $discountType = 'Persentase',
        float $discountValue = 20,
    ): array {
        return [
            'pool_id' => $poolId,
            'nama_promo' => $name,
            'deskripsi' => 'Promo tiket kolam renang.',
            'jenis_diskon' => $discountType,
            'nilai_diskon' => $discountValue,
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'status' => $active,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function addPrice(string $poolId, string $dayType, int $price, string $category = 'Dewasa'): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => $poolId,
            'kategori' => $category,
            'jenis_hari' => $dayType,
            'harga' => $price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @param  array<string, int>  $overrides
     * @return array<string, int|string>
     */
    private function reservationPayload(array $overrides = []): array
    {
        return array_merge([
            'pool_id' => 'pool_id_01',
            'nama_pengunjung' => 'Test Pengunjung',
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-18',
            'jumlah_dewasa' => 2,
            'jumlah_anak' => 0,
            'total_harga' => 32000,
        ], $overrides);
    }
}
