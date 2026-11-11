<?php

namespace Tests\Feature;

use App\Mail\AdminApplicationApproved;
use App\Mail\AdminApplicationRejected;
use App\Models\AdminApplication;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminApplicationFlowTest extends TestCase
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

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id')->unique();
            $table->string('nama_kolam');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('pool_id')->nullable();
            $table->string('role')->default('admin_pool');
            $table->boolean('must_change_password')->default(false);
            $table->string('position')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->unique(['pool_id', 'username']);
        });

        Schema::create('pengunjung', function (Blueprint $table): void {
            $table->id();
            $table->string('no_hp', 20)->unique();
        });

        Schema::create('admin_applications', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('username', 20);
            $table->string('email');
            $table->string('phone', 20);
            $table->string('position');
            $table->string('pool_id');
            $table->string('access_code', 20);
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();
        });

        $migration = require base_path('database/migrations/2026_11_11_121113_add_password_hash_to_admin_applications_table.php');
        $migration->up();

        DB::table('pools')->insert([
            ['pool_id' => 'pool_a', 'name' => 'Kolam A', 'created_at' => now(), 'updated_at' => now()],
            ['pool_id' => 'pool_b', 'name' => 'Kolam B', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('kolam_renang')->insert([
            ['pool_id' => 'pool_a', 'nama_kolam' => 'Kolam A', 'created_at' => now(), 'updated_at' => now()],
            ['pool_id' => 'pool_b', 'nama_kolam' => 'Kolam B', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_pool_admin_can_submit_an_application_with_a_hashed_password(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $input = $this->applicationInput();
        $input['pool_id'] = 'pool_b';
        $input['status'] = AdminApplication::STATUS_APPROVED;
        $input['password_hash'] = 'attacker-supplied-hash';

        $response = $this->actingAs($poolAdmin)->withSession($this->poolSession($poolAdmin))->post(
            route('admin.pool.applications.store'),
            $input
        );

        $response->assertRedirect(route('admin.pool.applications.index'));
        $this->assertDatabaseHas('admin_applications', [
            'full_name' => 'Admin Baru',
            'email' => 'admin.baru@example.test',
            'pool_id' => 'pool_a',
            'status' => AdminApplication::STATUS_PENDING,
        ]);

        $application = AdminApplication::query()->where('email', 'admin.baru@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('strong-password-123', $application->password_hash));
        $this->assertNotSame('strong-password-123', $application->password_hash);
        $this->assertArrayNotHasKey('password_hash', $application->toArray());
        $this->assertArrayNotHasKey('access_code', $application->toArray());
    }

    public function test_pool_admin_only_sees_applications_for_their_own_pool(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $this->storeApplication('Applicant A', 'applicant-a@example.test', 'pool_a');
        $this->storeApplication('Applicant B', 'applicant-b@example.test', 'pool_b', 'strong-password-123', '08123456788');

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->get(route('admin.pool.applications.index'))
            ->assertOk()
            ->assertSee('Applicant A')
            ->assertDontSee('Applicant B');
    }

    public function test_admin_master_can_approve_once_and_the_applicant_can_log_in(): void
    {
        Mail::fake();

        $master = $this->createAdmin('master@example.test', null, 'admin_master');
        $application = $this->storeApplication('Admin Baru', 'admin.baru@example.test', 'pool_a', 'strong-password-123');

        $response = $this->actingAs($master)
            ->withSession($this->masterSession($master))
            ->post(route('admin.master.applications.approve', $application));

        $response->assertRedirect();
        $this->assertDatabaseHas('admin_applications', [
            'id' => $application->id,
            'status' => AdminApplication::STATUS_APPROVED,
            'password_hash' => null,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'admin.baru@example.test',
            'pool_id' => 'pool_a',
            'role' => 'admin_pool',
            'must_change_password' => false,
        ]);

        $newAdmin = User::query()->where('email', 'admin.baru@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('strong-password-123', $newAdmin->password));

        Mail::assertSent(AdminApplicationApproved::class, fn (AdminApplicationApproved $mail): bool => $mail->plainPassword === null);

        $this->post(route('login.process'), [
            'email' => 'admin.baru@example.test',
            'password' => 'strong-password-123',
        ])->assertRedirect('/dashboard')
            ->assertSessionHas('admin_role', 'admin_pool')
            ->assertSessionHas('admin_pool_id', 'pool_a');

        $this->actingAs($master)
            ->withSession($this->masterSession($master))
            ->post(route('admin.master.applications.approve', $application))
            ->assertSessionHas('error', 'Pengajuan ini sudah diproses sebelumnya.');
        $this->assertDatabaseCount('users', 2);
    }

    public function test_admin_master_can_reject_without_creating_an_account_or_retaining_the_password_hash(): void
    {
        Mail::fake();

        $master = $this->createAdmin('master@example.test', null, 'admin_master');
        $application = $this->storeApplication('Admin Ditolak', 'ditolak@example.test', 'pool_a', 'strong-password-123');

        $this->actingAs($master)
            ->withSession($this->masterSession($master))
            ->post(route('admin.master.applications.reject', $application), [
                'rejection_reason' => 'Data identitas belum dapat diverifikasi.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('admin_applications', [
            'id' => $application->id,
            'status' => AdminApplication::STATUS_REJECTED,
            'password_hash' => null,
            'rejection_reason' => 'Data identitas belum dapat diverifikasi.',
        ]);
        $this->assertDatabaseMissing('users', ['email' => 'ditolak@example.test']);
        Mail::assertSent(AdminApplicationRejected::class);
    }

    public function test_admin_master_detail_does_not_render_password_or_password_hash(): void
    {
        $master = $this->createAdmin('master@example.test', null, 'admin_master');
        $application = $this->storeApplication('Admin Baru', 'admin.baru@example.test', 'pool_a', 'strong-password-123');

        $response = $this->actingAs($master)
            ->withSession($this->masterSession($master))
            ->get(route('admin.master.applications.index'));

        $response->assertOk()
            ->assertSee('Detail')
            ->assertSee('Kolam A')
            ->assertDontSee($application->password_hash)
            ->assertDontSee('strong-password-123')
            ->assertDontSee('password_hash');
    }

    public function test_admin_pool_cannot_access_master_routes_and_master_cannot_submit_for_a_pool(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $master = $this->createAdmin('master@example.test', null, 'admin_master');

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->get(route('admin.master.applications.index'))
            ->assertForbidden();

        $this->actingAs($master)
            ->withSession($this->masterSession($master))
            ->get(route('admin.pool.applications.index'))
            ->assertForbidden();
    }

    public function test_duplicate_email_and_mismatched_password_are_rejected(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $this->createAdmin('already-used@example.test', 'pool_b');

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->from(route('admin.pool.applications.index'))
            ->post(route('admin.pool.applications.store'), array_merge(
                $this->applicationInput(),
                ['email' => 'already-used@example.test', 'password_confirmation' => 'not-the-same']
            ))
            ->assertRedirect(route('admin.pool.applications.index'))
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('admin_applications', 0);
    }

    public function test_duplicate_phone_number_is_rejected(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        DB::table('pengunjung')->insert(['no_hp' => '08111111111']);

        $input = array_merge($this->applicationInput(), ['phone' => '08111111111']);

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->from(route('admin.pool.applications.index'))
            ->post(route('admin.pool.applications.store'), $input)
            ->assertRedirect(route('admin.pool.applications.index'))
            ->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('admin_applications', 0);
    }

    public function test_passwords_longer_than_the_bcrypt_byte_limit_are_rejected(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $longPassword = str_repeat('é', 40);
        $input = array_merge($this->applicationInput(), [
            'password' => $longPassword,
            'password_confirmation' => $longPassword,
        ]);

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->from(route('admin.pool.applications.index'))
            ->post(route('admin.pool.applications.store'), $input)
            ->assertRedirect(route('admin.pool.applications.index'))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseCount('admin_applications', 0);
    }

    public function test_non_string_email_and_password_input_is_rejected_without_a_server_error(): void
    {
        $poolAdmin = $this->createAdmin('admin-a@example.test', 'pool_a');
        $input = array_merge($this->applicationInput(), [
            'email' => ['admin.baru@example.test'],
            'password' => ['strong-password-123'],
            'password_confirmation' => 'strong-password-123',
        ]);

        $this->actingAs($poolAdmin)
            ->withSession($this->poolSession($poolAdmin))
            ->from(route('admin.pool.applications.index'))
            ->post(route('admin.pool.applications.store'), $input)
            ->assertRedirect(route('admin.pool.applications.index'))
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('admin_applications', 0);
    }

    public function test_pool_application_page_requires_an_admin_session(): void
    {
        $this->get(route('admin.pool.applications.index'))
            ->assertRedirect(route('login'));
    }

    private function createAdmin(string $email, ?string $poolId, string $role = 'admin_pool'): User
    {
        return User::create([
            'name' => $email,
            'full_name' => $email,
            'username' => str_replace(['@', '.'], '-', $email),
            'email' => $email,
            'password' => 'master-password-123',
            'pool_id' => $poolId,
            'role' => $role,
            'must_change_password' => false,
        ]);
    }

    private function storeApplication(
        string $name,
        string $email,
        string $poolId,
        string $password = 'strong-password-123',
        string $phone = '08123456789'
    ): AdminApplication {
        return AdminApplication::create([
            'full_name' => $name,
            'username' => 'applicant_'.strtolower(substr(md5($email), 0, 6)),
            'email' => $email,
            'phone' => $phone,
            'position' => 'staf_admin',
            'pool_id' => $poolId,
            'access_code' => 'A'.str_repeat('B', 19),
            'password_hash' => Hash::make($password),
            'status' => AdminApplication::STATUS_PENDING,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function applicationInput(): array
    {
        return [
            'full_name' => 'Admin Baru',
            'email' => 'admin.baru@example.test',
            'phone' => '08198765432',
            'position' => 'staf_admin',
            'password' => 'strong-password-123',
            'password_confirmation' => 'strong-password-123',
        ];
    }

    /**
     * @return array<string, int|string|bool>
     */
    private function poolSession(User $admin): array
    {
        return [
            'admin_id' => $admin->id,
            'admin_role' => 'admin_pool',
            'admin_pool_id' => $admin->pool_id,
            'admin_must_change_password' => false,
        ];
    }

    /**
     * @return array<string, int|string|bool>
     */
    private function masterSession(User $admin): array
    {
        return [
            'admin_id' => $admin->id,
            'admin_role' => 'admin_master',
            'admin_pool_id' => '',
            'admin_must_change_password' => false,
        ];
    }
}
