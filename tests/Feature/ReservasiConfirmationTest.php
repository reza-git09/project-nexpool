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

    private function poolAdminSession(): array
    {
        return [
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ];
    }
}
