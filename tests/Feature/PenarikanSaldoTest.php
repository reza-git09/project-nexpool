<?php

namespace Tests\Feature;

use App\Models\PenarikanSaldo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PenarikanSaldoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('pools', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id')->unique();
            $table->string('name');
            $table->string('registration_code')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        DB::table('pools')->insert([
            [
                'pool_id' => 'pool_01',
                'name' => 'Tiara Park',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pool_id' => 'pool_02',
                'name' => 'Dira Park',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $migration = require database_path(
            'migrations/2026_11_11_112717_create_penarikan_saldos_table.php',
        );
        $migration->up();
    }

    public function test_pool_admin_sees_only_its_withdrawals(): void
    {
        $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createWithdrawal('pool_02', 'BRI', '9876543210', '700000.00');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Penarikan Saldo')
            ->assertSee(route('penarikan-saldo.index'))
            ->assertSee('Ajukan Pencairan')
            ->assertSee('name="bank_name"', false)
            ->assertSee('name="account_number"', false)
            ->assertSee('name="amount"', false)
            ->assertSee('500.000,00')
            ->assertDontSee('700.000,00');
    }

    public function test_pool_admin_can_submit_a_withdrawal_without_balance_limit(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '123456789012',
                'amount' => '999999999999.99',
                'pool_id' => 'pool_02',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('penarikan_saldos', [
            'pool_id' => 'pool_01',
            'bank_name' => 'BCA',
            'account_number' => '123456789012',
            'amount' => '999999999999.99',
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);
    }

    public function test_withdrawal_factory_creates_a_withdrawal_for_a_pool(): void
    {
        $withdrawal = PenarikanSaldo::factory()->create();

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'pool_id' => $withdrawal->pool_id,
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);
    }

    public function test_pool_withdrawal_menu_is_not_hidden_by_a_stale_admin_master_role(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_master',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Penarikan Saldo')
            ->assertSee(route('penarikan-saldo.index'));
    }

    public function test_withdrawal_requires_a_valid_account_number_and_positive_amount(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '123',
                'amount' => '0',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHasErrors(['account_number', 'amount']);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'amount' => '10000000000000.00',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('penarikan_saldos', 0);
    }

    public function test_admin_master_can_complete_and_reject_pending_withdrawals(): void
    {
        $completed = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $rejected = $this->createWithdrawal('pool_02', 'BRI', '9876543210', '700000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.complete', $completed))
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $completed->id,
            'status' => PenarikanSaldo::STATUS_BERHASIL,
            'processed_by' => 10,
        ]);

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.reject', $rejected), [
                'rejection_reason' => 'Data rekening tidak sesuai.',
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $rejected->id,
            'status' => PenarikanSaldo::STATUS_DITOLAK,
            'processed_by' => 10,
            'rejection_reason' => 'Data rekening tidak sesuai.',
        ]);
    }

    public function test_admin_master_can_see_pool_and_copy_account_number(): void
    {
        $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->get(route('admin.master.penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Tiara Park')
            ->assertSee('BCA')
            ->assertSee('1234567890')
            ->assertSee('data-copy="1234567890"', false)
            ->assertSee('Cairkan Saldo')
            ->assertSee('Cairkan')
            ->assertSee('Tolak');
    }

    public function test_admin_master_cannot_process_the_same_withdrawal_twice(): void
    {
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.complete', $withdrawal))
            ->assertRedirect(route('admin.master.penarikan-saldo.index'));

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.complete', $withdrawal))
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHas('error', 'Pengajuan ini sudah diproses sebelumnya.');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_BERHASIL,
        ]);
    }

    public function test_pool_admin_cannot_access_master_withdrawal_actions(): void
    {
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->post(route('admin.master.penarikan-saldo.complete', $withdrawal))
            ->assertForbidden();

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);
    }

    private function createWithdrawal(
        string $poolId,
        string $bank,
        string $accountNumber,
        string $amount,
    ): PenarikanSaldo {
        return PenarikanSaldo::create([
            'pool_id' => $poolId,
            'bank_name' => $bank,
            'account_number' => $accountNumber,
            'amount' => $amount,
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);
    }

    private function createAdminMaster(): void
    {
        DB::table('users')->insert([
            'id' => 10,
            'name' => 'Admin Master',
            'email' => 'master@example.test',
            'password' => 'hashed-password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
