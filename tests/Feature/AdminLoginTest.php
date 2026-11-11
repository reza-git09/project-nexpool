<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('pool_id')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->nullable();
            $table->boolean('must_change_password')->default(false);
            $table->string('full_name')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function test_admin_login_page_only_shows_email_and_password_fields(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertDontSee('name="username"', false)
            ->assertDontSee('name="pool_id"', false)
            ->assertDontSee('Daftar');
    }

    public function test_admin_registration_page_and_submission_are_unavailable(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();
    }

    public function test_admin_can_log_in_with_email_and_password_without_selecting_a_pool(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Master',
            'username' => 'admin_master',
            'email' => 'admin@example.test',
            'password' => Hash::make('secret-password'),
            'role' => 'admin_master',
            'must_change_password' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post('/login', [
            'email' => 'ADMIN@example.test',
            'password' => 'secret-password',
        ])
            ->assertRedirect('/admin-master/dashboard')
            ->assertSessionHas('admin_role', 'admin_master');
    }

    public function test_admin_login_does_not_accept_a_username_instead_of_an_email(): void
    {
        $this->from('/login')
            ->post('/login', [
                'email' => 'admin_master',
                'password' => 'secret-password',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }
}
