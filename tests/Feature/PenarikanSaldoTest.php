<?php

namespace Tests\Feature;

use App\Models\PenarikanSaldo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
            $table->string('role')->nullable();
            $table->timestamps();
        });

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id')->unique();
            $table->string('nama_kolam');
            $table->timestamps();
        });

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('kode_reservasi')->nullable();
            $table->string('nama_pengunjung')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('status_pembayaran')->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
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

        $requesterMigration = require database_path(
            'migrations/2026_11_13_104444_add_requested_by_to_penarikan_saldos_table.php',
        );
        $requesterMigration->up();

        $proofMigration = require database_path(
            'migrations/2026_11_13_215500_add_transfer_proof_to_penarikan_saldos_table.php',
        );
        $proofMigration->up();
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
            ->assertSee('<select id="bank_name" name="bank_name"', false)
            ->assertSee('BCA (Bank Central Asia)')
            ->assertSee('BRI (Bank Rakyat Indonesia)')
            ->assertSee('Saldo tersedia untuk diajukan')
            ->assertSee('name="account_number"', false)
            ->assertSee('name="amount"', false)
            ->assertSee('data-max-amount="50000000"', false)
            ->assertSee('Maksimal Rp 50.000.000 per pengajuan; nominal lebih akan dibatasi.')
            ->assertSee('500.000,00')
            ->assertDontSee('700.000,00');
    }

    public function test_pool_admin_can_submit_a_withdrawal_up_to_current_pool_income(): void
    {
        $this->createPoolAdmin();

        DB::table('reservasis')->insert([
            [
                'pool_id' => 'pool_01',
                'total_harga' => '495000.05',
                'status_pembayaran' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pool_id' => 'pool_01',
                'total_harga' => '250000.00',
                'status_pembayaran' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '123456789012',
                'amount' => '450000.00',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('penarikan_saldos', [
            'pool_id' => 'pool_01',
            'bank_name' => 'BCA',
            'account_number' => '123456789012',
            'amount' => '450000.00',
            'requested_by' => 1,
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Saldo tersedia saat ini:')
            ->assertDontSee('Total pendapatan terbayar')
            ->assertDontSee('Total pencairan terkonfirmasi')
            ->assertSee('495.000,05');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '123456789013',
                'amount' => '495000.06',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHasErrors('amount');
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

    public function test_withdrawal_is_limited_to_fifty_million_per_request(): void
    {
        $this->createPoolAdmin();
        $this->createPaidReservation('pool_01', '60000000.00');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'amount' => '50000000.00',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'amount' => '50000000.01',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('penarikan_saldos', 1);
        $this->assertDatabaseHas('penarikan_saldos', [
            'pool_id' => 'pool_01',
            'amount' => '50000000.00',
        ]);
    }

    public function test_legacy_successful_withdrawal_without_proof_is_not_labeled_confirmed(): void
    {
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $withdrawal->forceFill([
            'status' => PenarikanSaldo::STATUS_BERHASIL,
        ])->save();

        $this->assertSame('Berhasil/Dicairkan', $withdrawal->status_label);
    }

    public function test_pool_admin_can_request_from_paid_revenue_without_reusing_legacy_withdrawals(): void
    {
        $this->createPoolAdmin();
        $this->createPaidReservation('pool_01', '438000.00');
        $legacyWithdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '740000.00');
        $legacyWithdrawal->forceFill([
            'status' => PenarikanSaldo::STATUS_BERHASIL,
        ])->save();

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Saldo tersedia saat ini: <strong>Rp 438.000,00</strong>', false)
            ->assertDontSee('Total pendapatan terbayar')
            ->assertDontSee('Total pencairan terkonfirmasi')
            ->assertSee('Saldo tersedia untuk diajukan')
            ->assertSee('Rp 438.000,00');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'BCA',
                'account_number' => '123456789012',
                'amount' => '20000.00',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('penarikan_saldos', [
            'pool_id' => 'pool_01',
            'amount' => '20000.00',
            'status' => PenarikanSaldo::STATUS_PROSES,
            'requested_by' => 1,
        ]);
        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $legacyWithdrawal->id,
            'status' => PenarikanSaldo::STATUS_BERHASIL,
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

    public function test_pool_admin_can_only_submit_a_bank_from_the_bank_dropdown(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->from(route('penarikan-saldo.index'))
            ->post(route('penarikan-saldo.store'), [
                'bank_name' => 'Bank Tidak Terdaftar',
                'account_number' => '1234567890',
                'amount' => '1000',
            ])
            ->assertRedirect(route('penarikan-saldo.index'))
            ->assertSessionHasErrors('bank_name');

        $this->assertDatabaseCount('penarikan_saldos', 0);
    }

    public function test_admin_master_confirms_withdrawal_when_sending_a_valid_transfer_proof(): void
    {
        Storage::fake('local');
        $this->createPaidReservation('pool_01', '1000000.00');
        $completed = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('1.000.000,00');

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->post(route('admin.master.penarikan-saldo.proof', $completed), [
                'transfer_proof' => UploadedFile::fake()->image('transfer.png'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $completed->refresh();
        $this->assertNotNull($completed->transfer_proof_path);
        Storage::disk('local')->assertExists($completed->transfer_proof_path);

        $response = $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->get(route('admin.master.penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Detail')
            ->assertSee('<span class="status-badge status-terkonfirmasi">Terkonfirmasi</span>', false)
            ->assertSee(route('admin.master.penarikan-saldo.proof.show', $completed), false);

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->get(route('admin.master.penarikan-saldo.proof.show', $completed))
            ->assertOk();

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Lihat')
            ->assertSee('data-proof-preview-url="'.route('penarikan-saldo.proof.show', $completed).'"', false)
            ->assertSee('id="proofPreviewModal"', false)
            ->assertDontSee('<img class="proof-preview"', false);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->get(route('penarikan-saldo.proof.show', $completed))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('Content-Disposition', 'inline; filename='.basename($completed->transfer_proof_path));

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $completed), [
                'transfer_proof' => UploadedFile::fake()->image('duplicate.png'),
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHas('error', 'Pengajuan ini sudah diproses sebelumnya.');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $completed->id,
            'status' => PenarikanSaldo::STATUS_TERKONFIRMASI,
            'transfer_proof_path' => $completed->transfer_proof_path,
            'processed_by' => 10,
        ]);

        $this->withSession([
            'admin_id' => 2,
            'admin_pool_id' => 'pool_02',
            'admin_role' => 'admin_pool',
        ])
            ->get(route('penarikan-saldo.proof.show', $completed))
            ->assertForbidden();

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $completed->id,
            'status' => PenarikanSaldo::STATUS_TERKONFIRMASI,
            'processed_by' => 10,
        ]);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Saldo tersedia saat ini:')
            ->assertSee('500.000,00')
            ->assertSee('Terkonfirmasi');
    }

    public function test_admin_master_can_view_read_only_withdrawal_details_and_send_proof(): void
    {
        $this->createPoolAdmin();
        $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00', 1);
        $this->createAdminMaster();

        $response = $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->get(route('admin.master.penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('Tiara Park')
            ->assertSee('Admin Pool')
            ->assertSee('<th>Tanggal Pengajuan</th>', false)
            ->assertSee('<th>Admin Pengaju</th>', false)
            ->assertSee('<th>Jumlah</th>', false)
            ->assertSee('<th>Aksi</th>', false)
            ->assertDontSee('<th>Wisata / Kolam Renang</th>', false)
            ->assertDontSee('<th>Bank</th>', false)
            ->assertDontSee('<th>Nomor Rekening</th>', false)
            ->assertSee('<span class="status-badge status-proses">Diproses</span>', false)
            ->assertSee('data-copy-account', false)
            ->assertSee('Salin')
            ->assertSee('data-bank="BCA"', false)
            ->assertSee('data-account="1234567890"', false)
            ->assertSee('data-amount="Rp 500.000,00"', false)
            ->assertSee('data-status="Diproses"', false)
            ->assertSee('Detail')
            ->assertSee('type="file"', false)
            ->assertSee('data-detail-proof-form', false)
            ->assertSee('data-proof-upload-url=', false)
            ->assertSee('Kirim')
            ->assertDontSee('data-proof-form', false)
            ->assertDontSee('Konfirmasi Pencairan')
            ->assertDontSee('name="rejection_reason"', false)
            ->assertDontSee('> Edit<', false);

        $responseContent = $response->getContent();
        $this->assertLessThan(
            strpos($responseContent, 'data-copy-account'),
            strpos($responseContent, 'id="withdrawalDetailModal"'),
        );
    }

    public function test_admin_master_cannot_confirm_pending_withdrawal_without_transfer_proof(): void
    {
        $this->createPaidReservation('pool_01', '1000000.00');
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal))
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHasErrors('transfer_proof');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_PROSES,
            'processed_at' => null,
        ]);
    }

    public function test_transfer_proof_upload_requires_an_allowed_file_type_and_size(): void
    {
        Storage::fake('local');
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream'),
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHasErrors('transfer_proof');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'transfer_proof_path' => null,
        ]);

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->image('oversized.png')->size(5121),
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHasErrors('transfer_proof');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_PROSES,
            'transfer_proof_path' => null,
        ]);
    }

    public function test_withdrawals_do_not_reduce_gross_admin_master_income(): void
    {
        $this->createPaidReservation('pool_01', '1000000.00');
        DB::table('kolam_renang')->insert([
            'pool_id' => 'pool_01',
            'nama_kolam' => 'Tiara Park',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $withdrawal = $this->createWithdrawal(
            'pool_01',
            'BCA',
            '1234567890',
            '1500000.00',
        );
        $withdrawal->forceFill([
            'status' => PenarikanSaldo::STATUS_TERKONFIRMASI,
        ])->save();
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->get(route('admin.master.transaksi.index'))
            ->assertOk()
            ->assertSee('<b>Rp 1.000.000</b>', false)
            ->assertSee('<strong>Rp 1.000.000</strong>', false);
    }

    public function test_admin_master_cannot_confirm_the_same_withdrawal_twice(): void
    {
        Storage::fake('local');
        $this->createPaidReservation('pool_01', '1000000.00');
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->image('transfer.png'),
            ])
            ->assertRedirect();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->image('duplicate.png'),
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHas('error', 'Pengajuan ini sudah diproses sebelumnya.');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_TERKONFIRMASI,
        ]);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
        ])
            ->get(route('penarikan-saldo.index'))
            ->assertOk()
            ->assertSee('500.000,00');
    }

    public function test_admin_master_cannot_confirm_a_withdrawal_above_the_current_balance(): void
    {
        Storage::fake('local');
        $this->createPaidReservation('pool_01', '400000.00');
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');
        $this->createAdminMaster();

        $this->withSession([
            'admin_id' => 10,
            'admin_role' => 'admin_master',
        ])
            ->from(route('admin.master.penarikan-saldo.index'))
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->image('transfer.png'),
            ])
            ->assertRedirect(route('admin.master.penarikan-saldo.index'))
            ->assertSessionHasErrors('transfer_proof');

        $this->assertDatabaseHas('penarikan_saldos', [
            'id' => $withdrawal->id,
            'status' => PenarikanSaldo::STATUS_PROSES,
            'processed_by' => null,
            'processed_at' => null,
        ]);
        Storage::disk('local')->assertEmpty();
    }

    public function test_pool_admin_cannot_access_master_withdrawal_actions(): void
    {
        $withdrawal = $this->createWithdrawal('pool_01', 'BCA', '1234567890', '500000.00');

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_01',
            'admin_role' => 'admin_pool',
        ])
            ->post(route('admin.master.penarikan-saldo.proof', $withdrawal), [
                'transfer_proof' => UploadedFile::fake()->image('transfer.png'),
            ])
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
        ?int $requesterId = null,
    ): PenarikanSaldo {
        return PenarikanSaldo::create([
            'pool_id' => $poolId,
            'bank_name' => $bank,
            'account_number' => $accountNumber,
            'amount' => $amount,
            'status' => PenarikanSaldo::STATUS_PROSES,
            'requested_by' => $requesterId,
        ]);
    }

    private function createPaidReservation(string $poolId, string $amount): void
    {
        DB::table('reservasis')->insert([
            'pool_id' => $poolId,
            'total_harga' => $amount,
            'status_pembayaran' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createPoolAdmin(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin Pool',
            'email' => 'pool@example.test',
            'password' => 'hashed-password',
            'created_at' => now(),
            'updated_at' => now(),
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
