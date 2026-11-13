<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HargaTiketControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('harga_tiket', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->enum('jenis_hari', ['Weekday', 'Weekend']);
            $table->decimal('harga', 12, 2);
            $table->timestamps();
            $table->unique(['pool_id', 'jenis_hari']);
        });
    }

    public function test_admin_price_pages_only_show_the_ticket_and_day_type(): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);

        $session = ['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'];

        $this->withSession($session)
            ->get('/harga-tiket')
            ->assertOk()
            ->assertSee('Jenis Hari')
            ->assertSee('Harga Tiket')
            ->assertSee('Aksi')
            ->assertSee('Penarikan Saldo')
            ->assertSee(route('penarikan-saldo.index'))
            ->assertDontSee('Kategori');

        $this->withSession($session)
            ->get('/harga-tiket/create')
            ->assertOk()
            ->assertSee('name="jenis_hari"', false)
            ->assertSee('name="harga"', false)
            ->assertSee('<option value="5000"', false)
            ->assertSee('<option value="100000"', false)
            ->assertSee('Penarikan Saldo')
            ->assertDontSee('name="kategori"', false);

        $this->withSession($session)
            ->get('/harga-tiket/1/edit')
            ->assertOk()
            ->assertSee('name="jenis_hari"', false)
            ->assertSee('name="harga"', false)
            ->assertSee('Penarikan Saldo')
            ->assertDontSee('name="kategori"', false);
    }

    public function test_admin_can_create_and_update_one_price_per_day_without_a_category(): void
    {
        $session = ['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'];

        $this->withSession($session)
            ->post('/harga-tiket', [
                'jenis_hari' => 'Weekday',
                'harga' => '20000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekday',
            'harga' => 20000,
        ]);

        $priceId = DB::table('harga_tiket')->value('id');

        $this->withSession($session)
            ->put('/harga-tiket/'.$priceId, [
                'jenis_hari' => 'Weekend',
                'harga' => '22000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'id' => $priceId,
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekend',
            'harga' => 22000,
        ]);
    }

    public function test_admin_cannot_add_a_duplicate_price_for_the_same_day(): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);

        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->from('/harga-tiket/create')
            ->post('/harga-tiket', [
                'jenis_hari' => 'Weekday',
                'harga' => '20000',
            ])
            ->assertRedirect('/harga-tiket/create')
            ->assertSessionHasErrors(
                'jenis_hari',
                "Data harga tiket untuk hari 'Weekday' sudah tersedia sebelumnya."
            );

        $this->assertDatabaseCount('harga_tiket', 1);
    }

    public function test_admin_cannot_create_ticket_price_outside_dropdown_options(): void
    {
        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->from('/harga-tiket/create')
            ->post('/harga-tiket', [
                'jenis_hari' => 'Weekday',
                'harga' => '22000',
            ])
            ->assertRedirect('/harga-tiket/create')
            ->assertSessionHasErrors('harga');

        $this->assertDatabaseCount('harga_tiket', 0);
    }

    public function test_ticket_price_api_returns_one_price_without_a_category(): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => 'pool_id_01',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);

        foreach (['/api/harga-tiket', '/api/harga-tiket/pool_id_01'] as $path) {
            $response = $this->getJson($path)
                ->assertOk()
                ->assertJsonPath('data.0.jenis_hari', 'Weekday');

            $this->assertArrayNotHasKey('kategori', $response->json('data.0'));
        }
    }
}
