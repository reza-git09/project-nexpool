<?php

namespace Tests\Feature;

use App\Models\Reservasi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReservasiConfirmationTest extends TestCase
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
            $table->unsignedInteger('jumlah_dewasa')->default(0);
            $table->unsignedInteger('jumlah_anak')->default(0);
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
    }

    public function test_paid_reservation_waits_for_status_change_in_manage_form(): void
    {
        $reservation = $this->createReservation('paid');

        $this->withSession($this->poolAdminSession())
            ->get(route('reservasi.index'))
            ->assertOk()
            ->assertSee('Lunas')
            ->assertSee('Kelola')
            ->assertDontSee('btn-confirm')
            ->assertDontSee('reservasi.confirm');

        $this->assertDatabaseHas('reservasis', [
            'id' => $reservation->id,
            'status_reservasi' => 'Menunggu',
            'status_pembayaran' => 'paid',
        ]);
    }

    public function test_admin_changes_paid_reservation_to_confirmed_through_manage_form(): void
    {
        $reservation = $this->createReservation('paid');

        $this->withSession($this->poolAdminSession())
            ->put(route('reservasi.update', $reservation), [
                'status_reservasi' => 'Dikonfirmasi',
            ])
            ->assertRedirect(route('reservasi.dikonfirmasi'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reservasis', [
            'id' => $reservation->id,
            'status_reservasi' => 'Dikonfirmasi',
            'status_pembayaran' => 'paid',
        ]);
    }

    public function test_unpaid_reservation_cannot_be_changed_to_confirmed(): void
    {
        $reservation = $this->createReservation('pending');

        $this->withSession($this->poolAdminSession())
            ->from(route('reservasi.edit', $reservation))
            ->put(route('reservasi.update', $reservation), [
                'status_reservasi' => 'Dikonfirmasi',
            ])
            ->assertRedirect(route('reservasi.edit', $reservation))
            ->assertSessionHasErrors('status_reservasi');

        $this->assertDatabaseHas('reservasis', [
            'id' => $reservation->id,
            'status_reservasi' => 'Menunggu',
            'status_pembayaran' => 'pending',
        ]);
    }

    public function test_admin_cannot_confirm_a_reservation_from_another_pool(): void
    {
        $reservation = $this->createReservation('paid', 'pool_id_02');

        $this->withSession($this->poolAdminSession())
            ->put(route('reservasi.update', $reservation), [
                'status_reservasi' => 'Dikonfirmasi',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('reservasis', [
            'id' => $reservation->id,
            'status_reservasi' => 'Menunggu',
        ]);
    }

    public function test_midtrans_notification_marks_reservation_paid_once(): void
    {
        $this->travelTo('2026-11-14 08:00:00');
        $serverKey = 'test-midtrans-server-key';
        config(['services.midtrans.server_key' => $serverKey]);
        $reservation = $this->createReservation('pending');
        $orderId = 'NEXPOOL-'.$reservation->kode_reservasi;
        $reservation->forceFill(['midtrans_order_id' => $orderId])->save();
        $notification = $this->signedMidtransNotification(
            $orderId,
            '25000.00',
            $serverKey,
        );

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertOk()
            ->assertJsonPath('success', true);

        $firstPaidAt = $reservation->refresh()->paid_at?->toIso8601String();
        $this->assertNotNull($firstPaidAt);
        $this->travel(5)->minutes();

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertOk()
            ->assertJsonPath('success', true);

        $reservation->refresh();
        $this->assertSame('paid', $reservation->status_pembayaran);
        $this->assertSame('TRX-'.$orderId, $reservation->midtrans_transaction_id);
        $this->assertSame($firstPaidAt, $reservation->paid_at?->toIso8601String());
        $this->assertDatabaseCount('reservasis', 1);
    }

    public function test_midtrans_notification_rejects_a_mismatched_amount(): void
    {
        $serverKey = 'test-midtrans-server-key';
        config(['services.midtrans.server_key' => $serverKey]);
        $reservation = $this->createReservation('pending');
        $orderId = 'NEXPOOL-'.$reservation->kode_reservasi;
        $reservation->forceFill(['midtrans_order_id' => $orderId])->save();
        $notification = $this->signedMidtransNotification(
            $orderId,
            '25001.00',
            $serverKey,
        );

        $this->postJson('/api/midtrans/notification', $notification)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Nominal pembayaran tidak sesuai.');

        $this->assertDatabaseHas('reservasis', [
            'id' => $reservation->id,
            'status_pembayaran' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function test_mobile_reschedule_updates_the_date_seen_by_pool_admin(): void
    {
        $this->travelTo('2026-11-14 08:00:00');
        $reservation = $this->createReservation('paid');
        $reservation->forceFill([
            'tanggal_kunjungan' => '2026-11-20',
        ])->save();

        $this->putJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-25',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.tanggal_kunjungan', '2026-11-25');

        $this->assertSame(
            '2026-11-25',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );

        $this->withSession($this->poolAdminSession())
            ->get(route('reservasi.index'))
            ->assertOk()
            ->assertSee('25-11-2026');
    }

    public function test_mobile_reschedule_allows_a_reservation_on_its_visit_date(): void
    {
        $this->travelTo('2026-11-14 08:00:00');
        $reservation = $this->createReservation('paid');
        $reservation->forceFill([
            'tanggal_kunjungan' => '2026-11-14',
        ])->save();

        $this->putJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-15',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.tanggal_kunjungan', '2026-11-15');

        $this->assertSame(
            '2026-11-15',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_mobile_reschedule_rejects_a_past_visit_date(): void
    {
        $this->travelTo('2026-11-14 08:00:00');
        $reservation = $this->createReservation('paid');

        $this->putJson('/api/reservasi/kode/'.$reservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-15',
        ])
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                'Reschedule tidak dapat dilakukan setelah tanggal kunjungan.',
            );

        $this->assertSame(
            '2026-11-13',
            $reservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    public function test_mobile_reschedule_requires_the_matching_phone_and_paid_status(): void
    {
        $this->travelTo('2026-11-14 08:00:00');
        $paidReservation = $this->createReservation('paid');
        $paidReservation->forceFill([
            'tanggal_kunjungan' => '2026-11-20',
        ])->save();

        $this->putJson('/api/reservasi/kode/'.$paidReservation->kode_reservasi.'/reschedule', [
            'no_hp' => '089999999999',
            'tanggal_kunjungan' => '2026-11-25',
        ])->assertForbidden();

        $unpaidReservation = $this->createReservation('pending');
        $unpaidReservation->forceFill([
            'tanggal_kunjungan' => '2026-11-20',
        ])->save();

        $this->putJson('/api/reservasi/kode/'.$unpaidReservation->kode_reservasi.'/reschedule', [
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-25',
        ])->assertUnprocessable();

        $this->assertSame(
            '2026-11-20',
            $paidReservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
        $this->assertSame(
            '2026-11-20',
            $unpaidReservation->refresh()->tanggal_kunjungan->format('Y-m-d'),
        );
    }

    private function createReservation(string $paymentStatus, string $poolId = 'pool_id_01'): Reservasi
    {
        return Reservasi::query()->create([
            'kode_reservasi' => 'RSV-'.strtoupper(fake()->unique()->bothify('######')),
            'pool_id' => $poolId,
            'nama_pengunjung' => 'Pengunjung Test',
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-13',
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 0,
            'total_harga' => 25000,
            'status_reservasi' => 'Menunggu',
            'status_pembayaran' => $paymentStatus,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function signedMidtransNotification(
        string $orderId,
        string $grossAmount,
        string $serverKey,
    ): array {
        $notification = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'transaction_id' => 'TRX-'.$orderId,
        ];
        $notification['signature_key'] = hash(
            'sha512',
            $orderId.$notification['status_code'].$grossAmount.$serverKey,
        );

        return $notification;
    }

    private function poolAdminSession(): array
    {
        return [
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ];
    }
}
