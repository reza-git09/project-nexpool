<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KolamRenangApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id')->unique();
            $table->string('nama_kolam');
            $table->text('alamat');
            $table->string('kota')->default('Jember');
            $table->text('deskripsi')->nullable();
            $table->string('maps_url')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function test_it_returns_all_swimming_pools_in_the_api_response(): void
    {
        DB::table('kolam_renang')->insert([
            $this->pool('pool_01', 'Tiara Park'),
            $this->pool('pool_02', 'Dira Park'),
        ]);

        $this->getJson('/api/kolam-renang')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Data kolam renang berhasil diambil')
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'pool_id' => 'pool_01',
                'nama_kolam' => 'Tiara Park',
            ])
            ->assertJsonFragment([
                'pool_id' => 'pool_02',
                'nama_kolam' => 'Dira Park',
            ]);
    }

    public function test_it_returns_an_empty_data_list_when_no_pool_exists(): void
    {
        $this->getJson('/api/kolam-renang')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', []);
    }

    /**
     * @return array<string, bool|string>
     */
    private function pool(string $poolId, string $name): array
    {
        return [
            'pool_id' => $poolId,
            'nama_kolam' => $name,
            'alamat' => 'Jalan Kolam',
            'kota' => 'Jember',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
