<?php

namespace Tests\Feature;

use App\Models\HargaTiket;
use App\Models\RescheduleRequest;
use App\Models\Reservasi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RescheduleWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_reservasi')->unique();
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->string('no_hp');
            $table->date('tanggal_kunjungan');
            $table->integer('jumlah_dewasa')->default(0);
            $table->integer('jumlah_anak')->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->string('status_reservasi')->default('Menunggu');
            $table->string('status_pembayaran')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_qr_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('harga_tiket', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('jenis_hari');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });

        Schema::create('reschedule_requests', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('reservasi_id');
            $table->date('tanggal_lama');
            $table->date('tanggal_baru');
            $table->unsignedBigInteger('selisih_harga')->default(0);
            $table->string('status', 32);
            $table->string('alasan_penolakan', 500)->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('approved_at')->nullable();
            $table->string('payment_status', 24)->nullable();
            $table->string('payment_order_id')->nullable()->unique();
            $table->string('payment_transaction_id')->nullable();
            $table->text('payment_qr_url')->nullable();
            $table->timestamp('payment_expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['reservasi_id', 'status', 'expires_at']);
        });
    }

    public function test_weekend_request_waits_for_approval_and_returns_the_difference(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();

        $this->addTicketPrices();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-28',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', RescheduleRequest::STATUS_AWAITING_APPROVAL)
            ->assertJsonPath('data.selisih_harga', 10000);

        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertDatabaseCount('reschedule_requests', 1);
    }

    public function test_quote_returns_the_difference_without_creating_a_request(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();

        $this->getJson(
            '/api/reservasi/kode/'.$reservation->kode_reservasi
                .'/reschedule/quote?no_hp=081234567890&tanggal_kunjungan=2026-11-28',
        )
            ->assertOk()
            ->assertJsonPath('data.tanggal_lama', '2026-11-25')
            ->assertJsonPath('data.tanggal_baru', '2026-11-28')
            ->assertJsonPath('data.selisih_harga', 10000);

        $this->assertDatabaseCount('reschedule_requests', 0);
    }

    public function test_second_request_is_rejected_while_the_first_is_active(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $payload = [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-28',
        ];

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', $payload)
            ->assertCreated();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            ...$payload,
            'tanggal_kunjungan' => '2026-11-29',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Masih ada pengajuan reschedule aktif untuk tiket ini.');

        $this->assertDatabaseCount('reschedule_requests', 1);
        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_request_rejects_a_new_date_before_h2(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-21',
        ])
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                'Tanggal baru reschedule minimal H+2 dari hari ini.',
            );

        $this->assertDatabaseCount('reschedule_requests', 0);
    }

    public function test_ticket_for_today_can_be_rescheduled_to_a_date_at_least_two_days_ahead(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $reservation->forceFill(['tanggal_kunjungan' => '2026-11-20'])->save();
        $this->addTicketPrices();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-22',
        ])
            ->assertCreated()
            ->assertJsonPath('data.tanggal_lama', '2026-11-20')
            ->assertJsonPath('data.tanggal_baru', '2026-11-22');

        $this->assertSame(
            '2026-11-20',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertDatabaseCount('reschedule_requests', 1);
    }

    public function test_ticket_from_a_past_visit_date_cannot_be_rescheduled(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $reservation->forceFill(['tanggal_kunjungan' => '2026-11-19'])->save();
        $this->addTicketPrices();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-22',
        ])
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                'Reschedule tidak dapat dilakukan setelah tanggal kunjungan.',
            );

        $this->assertSame(
            '2026-11-19',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertDatabaseCount('reschedule_requests', 0);
    }

    public function test_used_ticket_cannot_be_rescheduled(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $reservation->forceFill(['status_reservasi' => 'Selesai'])->save();
        $this->addTicketPrices();

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-28',
        ])
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                'Tiket yang dibatalkan atau sudah digunakan tidak dapat dijadwalkan ulang.',
            );

        $this->assertDatabaseCount('reschedule_requests', 0);
    }

    public function test_admin_approval_waits_for_payment_before_changing_the_date(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);

        $this->withSession($this->poolAdminSession())
            ->get(route('reservasi.index'))
            ->assertOk()
            ->assertSee('Daftar Reservasi')
            ->assertSee($reservation->kode_reservasi)
            ->assertSee('Reschedule · Menunggu Persetujuan')
            ->assertSee('Setujui')
            ->assertSee('Tolak')
            ->assertDontSee('Pengajuan Reschedule Menunggu Persetujuan');

        $this->withSession($this->poolAdminSession())
            ->post(route('reservasi.reschedule-requests.approve', $rescheduleRequest))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(
            RescheduleRequest::STATUS_AWAITING_PAYMENT,
            $rescheduleRequest->refresh()->status,
        );
        $this->withSession($this->poolAdminSession())
            ->get(route('reservasi.index'))
            ->assertOk()
            ->assertSee('Reschedule · Disetujui · Menunggu Pembayaran')
            ->assertDontSee('Setujui');
        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_rejected_request_shows_reason_and_allows_a_new_request(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);

        $this->withSession($this->poolAdminSession())
            ->from(route('reservasi.index'))
            ->post(route('reservasi.reschedule-requests.reject', $rescheduleRequest), [
                'alasan_penolakan' => 'Kuota akhir pekan sudah penuh.',
            ])
            ->assertRedirect(route('reservasi.index'))
            ->assertSessionHas('success');

        $this->assertSame(
            'Kuota akhir pekan sudah penuh.',
            $rescheduleRequest->refresh()->alasan_penolakan,
        );

        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-29',
        ])->assertCreated();

        $this->assertDatabaseCount('reschedule_requests', 2);
    }

    public function test_verified_difference_payment_changes_the_date_without_changing_the_ticket_total(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $serverKey = 'test-midtrans-server-key';
        config(['services.midtrans.server_key' => $serverKey]);
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);
        $rescheduleRequest->forceFill([
            'status' => RescheduleRequest::STATUS_AWAITING_PAYMENT,
            'payment_status' => 'pending',
            'payment_order_id' => 'RESCHEDULE-'.$rescheduleRequest->id.'-TEST1234',
            'approved_at' => now(),
        ])->save();
        $notification = $this->signedNotification(
            $rescheduleRequest->payment_order_id,
            '10000.00',
            $serverKey,
        );

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(
            RescheduleRequest::STATUS_COMPLETED,
            $rescheduleRequest->refresh()->status,
        );
        $this->assertSame(
            '2026-11-28',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertSame('32000.00', $reservation->total_harga);
    }

    public function test_mismatched_difference_payment_does_not_change_the_date(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $serverKey = 'test-midtrans-server-key';
        config(['services.midtrans.server_key' => $serverKey]);
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);
        $rescheduleRequest->forceFill([
            'status' => RescheduleRequest::STATUS_AWAITING_PAYMENT,
            'payment_status' => 'pending',
            'payment_order_id' => 'RESCHEDULE-'.$rescheduleRequest->id.'-TEST1234',
        ])->save();
        $notification = $this->signedNotification(
            $rescheduleRequest->payment_order_id,
            '10001.00',
            $serverKey,
        );

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Nominal pembayaran tidak sesuai.');

        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertSame(
            RescheduleRequest::STATUS_AWAITING_PAYMENT,
            $rescheduleRequest->refresh()->status,
        );
    }

    public function test_expired_qris_keeps_the_approved_request_active(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $serverKey = 'test-midtrans-server-key';
        config(['services.midtrans.server_key' => $serverKey]);
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);
        $orderId = 'RESCHEDULE-'.$rescheduleRequest->id.'-TEST1234';
        $rescheduleRequest->forceFill([
            'status' => RescheduleRequest::STATUS_AWAITING_PAYMENT,
            'payment_status' => 'pending',
            'payment_order_id' => $orderId,
            'payment_expires_at' => now()->subSecond(),
            'expires_at' => now()->addHours(24),
        ])->save();
        $notification = $this->signedNotification(
            $orderId,
            '10000.00',
            $serverKey,
            'expire',
        );

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(
            RescheduleRequest::STATUS_AWAITING_PAYMENT,
            $rescheduleRequest->refresh()->status,
        );
        $this->assertSame('expired', $rescheduleRequest->payment_status);
        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_expired_request_becomes_inactive_and_can_be_replaced(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);
        $this->travel(25)->hours();

        $this->getJson(
            '/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule?no_hp=081234567890',
        )
            ->assertOk()
            ->assertJsonPath('data.request.status', RescheduleRequest::STATUS_EXPIRED);

        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-29',
        ])->assertCreated();
        $this->assertDatabaseCount('reschedule_requests', 2);
        $this->assertSame(
            RescheduleRequest::STATUS_EXPIRED,
            $rescheduleRequest->refresh()->status,
        );
    }

    public function test_payment_attempt_persists_approval_expiration(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation();
        $this->addTicketPrices();
        $rescheduleRequest = $this->submitRequest($reservation);
        $rescheduleRequest->forceFill([
            'status' => RescheduleRequest::STATUS_AWAITING_PAYMENT,
        ])->save();
        $this->travel(25)->hours();

        $this->postJson(
            '/api/reservasi/kode/'.$reservation->kode_reservasi
                .'/reschedule/'.$rescheduleRequest->id.'/payment',
            [
                'reservation_code' => $reservation->kode_reservasi,
                'no_hp' => '081234567890',
            ],
        )
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Persetujuan reschedule sudah kedaluwarsa.');

        $this->assertSame(
            RescheduleRequest::STATUS_EXPIRED,
            $rescheduleRequest->refresh()->status,
        );
        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_admin_cannot_approve_a_request_from_another_pool(): void
    {
        $this->travelTo('2026-11-20 08:00:00');
        $reservation = $this->createReservation('pool_id_02');
        $this->addTicketPrices('pool_id_02');
        $rescheduleRequest = $this->submitRequest($reservation);

        $this->withSession($this->poolAdminSession())
            ->post(route('reservasi.reschedule-requests.approve', $rescheduleRequest))
            ->assertForbidden();

        $this->assertSame(
            RescheduleRequest::STATUS_AWAITING_APPROVAL,
            $rescheduleRequest->refresh()->status,
        );
    }

    private function createReservation(string $poolId = 'pool_id_01'): Reservasi
    {
        return Reservasi::query()->create([
            'kode_reservasi' => 'RSV-'.strtoupper(fake()->unique()->bothify('######')),
            'pool_id' => $poolId,
            'nama_pengunjung' => 'Pengunjung Test',
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-25',
            'jumlah_dewasa' => 2,
            'jumlah_anak' => 0,
            'total_harga' => 32000,
            'status_reservasi' => 'Menunggu',
            'status_pembayaran' => 'paid',
        ]);
    }

    private function addTicketPrices(string $poolId = 'pool_id_01'): void
    {
        HargaTiket::query()->create([
            'pool_id' => $poolId,
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);
        HargaTiket::query()->create([
            'pool_id' => $poolId,
            'jenis_hari' => 'Weekend',
            'harga' => 20000,
        ]);
    }

    private function submitRequest(Reservasi $reservation): RescheduleRequest
    {
        $this->postJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-28',
        ])->assertCreated();

        return RescheduleRequest::query()->latest('id')->firstOrFail();
    }

    /**
     * @return array<string, string>
     */
    private function signedNotification(
        string $orderId,
        string $grossAmount,
        string $serverKey,
        string $transactionStatus = 'settlement',
    ): array {
        $notification = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'transaction_status' => $transactionStatus,
            'transaction_id' => 'TRX-'.$orderId,
        ];
        $notification['signature_key'] = hash(
            'sha512',
            $orderId.$notification['status_code'].$grossAmount.$serverKey,
        );

        return $notification;
    }

    /**
     * @return array<string, int|string>
     */
    private function poolAdminSession(): array
    {
        return [
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ];
    }
}
