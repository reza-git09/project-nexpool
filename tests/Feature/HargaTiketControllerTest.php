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
            $table->enum('kategori', ['Dewasa', 'Anak']);
            $table->enum('jenis_hari', ['Weekday', 'Weekend']);
            $table->decimal('harga', 12, 2);
            $table->timestamps();
            $table->unique(['pool_id', 'kategori', 'jenis_hari']);
        });
    }

    public function test_admin_price_pages_show_category_for_ticket_prices(): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => 'pool_id_01',
            'kategori' => 'Dewasa',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);

        $session = ['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'];

        $this->withSession($session)
            ->get('/harga-tiket')
            ->assertOk()
            ->assertSee('Kategori')
            ->assertSee('Dewasa')
            ->assertSee('Jenis Hari')
            ->assertSee('Harga Tiket')
            ->assertSee('Aksi')
            ->assertSee('Penarikan Saldo')
            ->assertSee(route('penarikan-saldo.index'));

        $this->withSession($session)
            ->get('/harga-tiket/create')
            ->assertOk()
            ->assertSee('name="kategori"', false)
            ->assertSee('value="Dewasa"', false)
            ->assertSee('value="Anak"', false)
            ->assertSee('name="jenis_hari"', false)
            ->assertSee('name="harga"', false)
            ->assertSee('<option value="5000"', false)
            ->assertSee('<option value="100000"', false)
            ->assertSee('<option value="custom"', false)
            ->assertSee('name="harga_custom"', false)
            ->assertSee('Penarikan Saldo');

        $this->withSession($session)
            ->get('/harga-tiket/1/edit')
            ->assertOk()
            ->assertSee('name="kategori"', false)
            ->assertSee('value="Dewasa"', false)
            ->assertSee('value="Anak"', false)
            ->assertSee('name="jenis_hari"', false)
            ->assertSee('name="harga"', false)
            ->assertSee('Penarikan Saldo');
    }

    public function test_admin_can_create_and_update_a_category_ticket_price(): void
    {
        $session = ['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'];

        $this->withSession($session)
            ->post('/harga-tiket', [
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => '20000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'kategori' => 'Dewasa',
            'jenis_hari' => 'Weekday',
            'harga' => 20000,
        ]);

        $priceId = DB::table('harga_tiket')->value('id');

        $this->withSession($session)
            ->put('/harga-tiket/'.$priceId, [
                'kategori' => 'Anak',
                'jenis_hari' => 'Weekend',
                'harga' => '22000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'id' => $priceId,
            'pool_id' => 'pool_id_01',
            'kategori' => 'Anak',
            'jenis_hari' => 'Weekend',
            'harga' => 22000,
        ]);
    }

    public function test_admin_can_store_adult_and_child_prices_for_the_same_day(): void
    {
        $session = ['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'];

        $this->withSession($session)
            ->post('/harga-tiket', [
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => '20000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->withSession($session)
            ->post('/harga-tiket', [
                'kategori' => 'Anak',
                'jenis_hari' => 'Weekday',
                'harga' => '15000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'kategori' => 'Dewasa',
            'jenis_hari' => 'Weekday',
            'harga' => 20000,
        ]);
        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'kategori' => 'Anak',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);
        $this->assertDatabaseCount('harga_tiket', 2);
    }

    public function test_admin_cannot_create_a_ticket_price_with_an_invalid_category(): void
    {
        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->from('/harga-tiket/create')
            ->post('/harga-tiket', [
                'kategori' => 'Balita',
                'jenis_hari' => 'Weekday',
                'harga' => '15000',
            ])
            ->assertRedirect('/harga-tiket/create')
            ->assertSessionHasErrors([
                'kategori' => 'Kategori tiket tidak valid.',
            ]);

        $this->assertDatabaseCount('harga_tiket', 0);
    }

    public function test_admin_cannot_add_a_duplicate_price_for_the_same_day(): void
    {
        DB::table('harga_tiket')->insert([
            'pool_id' => 'pool_id_01',
            'kategori' => 'Dewasa',
            'jenis_hari' => 'Weekday',
            'harga' => 15000,
        ]);

        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->from('/harga-tiket/create')
            ->post('/harga-tiket', [
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => '20000',
            ])
            ->assertRedirect('/harga-tiket/create')
            ->assertSessionHasErrors(
                'jenis_hari',
                "Data harga tiket kategori 'Dewasa' untuk hari 'Weekday' sudah tersedia sebelumnya."
            );

        $this->assertDatabaseCount('harga_tiket', 1);
    }

    public function test_admin_can_create_a_custom_ticket_price(): void
    {
        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->post('/harga-tiket', [
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => 'custom',
                'harga_custom' => '125000',
            ])
            ->assertRedirect('/harga-tiket');

        $this->assertDatabaseHas('harga_tiket', [
            'pool_id' => 'pool_id_01',
            'kategori' => 'Dewasa',
            'jenis_hari' => 'Weekday',
            'harga' => 125000,
        ]);
    }

    public function test_admin_cannot_create_a_custom_ticket_price_below_minimum(): void
    {
        $this->withSession(['admin_id' => 1, 'admin_pool_id' => 'pool_id_01'])
            ->from('/harga-tiket/create')
            ->post('/harga-tiket', [
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => 'custom',
                'harga_custom' => '999',
            ])
            ->assertRedirect('/harga-tiket/create')
            ->assertSessionHasErrors('harga_custom');

        $this->assertDatabaseCount('harga_tiket', 0);
    }

    public function test_ticket_price_api_returns_each_price_with_its_category(): void
    {
        DB::table('harga_tiket')->insert([
            [
                'pool_id' => 'pool_id_01',
                'kategori' => 'Dewasa',
                'jenis_hari' => 'Weekday',
                'harga' => 15000,
            ],
            [
                'pool_id' => 'pool_id_01',
                'kategori' => 'Anak',
                'jenis_hari' => 'Weekday',
                'harga' => 10000,
            ],
        ]);

        foreach (['/api/harga-tiket', '/api/harga-tiket/pool_id_01'] as $path) {
            $response = $this->getJson($path)
                ->assertOk()
                ->assertJsonCount(2, 'data')
                ->assertJsonFragment([
                    'kategori' => 'Dewasa',
                    'jenis_hari' => 'Weekday',
                ])
                ->assertJsonFragment([
                    'kategori' => 'Anak',
                    'jenis_hari' => 'Weekday',
                ]);
        }
    }
}
