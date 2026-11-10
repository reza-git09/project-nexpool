<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PengunjungAuthApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('pengunjung', function (Blueprint $table): void {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->timestamps();
        });

        $migration = require database_path(
            'migrations/2026_11_10_100110_make_pengunjung_username_and_alamat_nullable.php'
        );
        $migration->up();

        Schema::create('otp_codes', function (Blueprint $table): void {
            $table->id();
            $table->string('phone', 20);
            $table->string('code_hash', 64);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('token_hash', 64)->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function test_registration_creates_an_account_without_username_or_address(): void
    {
        $token = 'verified-registration-token';
        $this->createVerifiedOtp('628123456789', $token);

        $response = $this->postJson('/api/register', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'no_hp' => '08123456789',
            'password' => 'password123',
            'otp_token' => $token,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.nama', 'Budi Santoso')
            ->assertJsonPath('data.no_hp', '628123456789');

        $this->assertDatabaseHas('pengunjung', [
            'email' => 'budi@example.test',
            'username' => null,
            'alamat' => null,
        ]);
        $this->assertArrayNotHasKey('username', $response->json('data'));
        $this->assertArrayNotHasKey('alamat', $response->json('data'));
    }

    public function test_login_accepts_an_email_address(): void
    {
        $this->createPengunjung('Siti Aminah', 'siti@example.test', '628111111111', 'password123');

        $this->postJson('/api/login', [
            'login' => 'siti@example.test',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('data.email', 'siti@example.test');
    }

    public function test_login_accepts_a_phone_number(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');

        $this->postJson('/api/login', [
            'login' => '08123456789',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('data.nama', 'Rina Putri');
    }

    public function test_login_does_not_accept_a_full_name_as_an_identifier(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');

        $this->postJson('/api/login', [
            'login' => 'Rina Putri',
            'password' => 'password123',
        ])->assertUnauthorized();
    }

    public function test_password_recovery_checks_for_an_account_by_phone_number(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');

        $this->postJson('/api/check-account', [
            'no_hp' => '08123456789',
        ])->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_password_recovery_requires_a_phone_verified_by_otp(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');
        $token = 'verified-reset-token';
        $this->createVerifiedOtp('628123456789', $token);

        $this->postJson('/api/reset-password', [
            'no_hp' => '08123456789',
            'otp_token' => $token,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertOk()
            ->assertJsonPath('success', true);

        $this->assertTrue(Hash::check(
            'newpassword123',
            DB::table('pengunjung')->where('email', 'rina@example.test')->value('password')
        ));
        $this->assertNotNull(DB::table('otp_codes')->value('used_at'));
    }

    public function test_password_recovery_rejects_an_invalid_or_used_otp(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');

        $this->postJson('/api/reset-password', [
            'no_hp' => '08123456789',
            'otp_token' => 'invalid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertUnprocessable();

        $this->assertTrue(Hash::check(
            'password123',
            DB::table('pengunjung')->where('email', 'rina@example.test')->value('password')
        ));
    }

    public function test_migration_rollback_restores_required_legacy_fields(): void
    {
        $this->createPengunjung('Rina Putri', 'rina@example.test', '628123456789', 'password123');

        $migration = require database_path(
            'migrations/2026_11_10_100110_make_pengunjung_username_and_alamat_nullable.php'
        );
        $migration->down();

        $pengunjung = DB::table('pengunjung')
            ->where('email', 'rina@example.test')
            ->first();

        $this->assertNotNull($pengunjung->username);
        $this->assertSame('', $pengunjung->alamat);
    }

    private function createPengunjung(
        string $name,
        string $email,
        string $phone,
        string $password
    ): void {
        DB::table('pengunjung')->insert([
            'nama' => $name,
            'email' => $email,
            'username' => null,
            'password' => Hash::make($password),
            'no_hp' => $phone,
            'alamat' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createVerifiedOtp(string $phone, string $token): void
    {
        DB::table('otp_codes')->insert([
            'phone' => $phone,
            'code_hash' => hash('sha256', '123456'),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
            'verified_at' => now(),
            'token_hash' => hash('sha256', $token),
            'used_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
