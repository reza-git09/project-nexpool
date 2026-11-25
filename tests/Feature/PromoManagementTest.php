<?php

namespace Tests\Feature;

use App\Models\Promo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PromoManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('promos', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('nama_promo');
            $table->text('deskripsi')->nullable();
            $table->string('jenis_diskon');
            $table->decimal('nilai_diskon', 12, 2);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function test_create_form_shows_percentage_as_a_fixed_value(): void
    {
        $this->withSession($this->adminSession())
            ->get(route('promo.create'))
            ->assertOk()
            ->assertSee('Persentase (%)')
            ->assertSee('name="jenis_diskon" value="Persentase"', false)
            ->assertDontSee('<select name="jenis_diskon"', false);
    }

    public function test_edit_form_shows_percentage_as_a_fixed_value(): void
    {
        $promo = $this->createPromo();

        $this->withSession($this->adminSession())
            ->get(route('promo.edit', $promo))
            ->assertOk()
            ->assertSee('Persentase (%)')
            ->assertSee('name="jenis_diskon" value="Persentase"', false)
            ->assertDontSee('<select name="jenis_diskon"', false);
    }

    public function test_store_saves_a_percentage_promo(): void
    {
        $this->travelTo('2026-11-25 12:00:00');

        $this->withSession($this->adminSession())
            ->post(route('promo.store'), $this->promoInput())
            ->assertRedirect(route('promo.index'));

        $this->assertDatabaseHas('promos', [
            'nama_promo' => 'Promo Baru',
            'pool_id' => 'pool-test',
            'jenis_diskon' => 'Persentase',
            'nilai_diskon' => 20,
        ]);
    }

    public function test_store_rejects_a_nominal_promo(): void
    {
        $this->travelTo('2026-11-25 12:00:00');

        $this->from(route('promo.create'))
            ->withSession($this->adminSession())
            ->post(route('promo.store'), $this->promoInput(['jenis_diskon' => 'Nominal']))
            ->assertRedirect(route('promo.create'))
            ->assertSessionHasErrors('jenis_diskon');

        $this->assertDatabaseCount('promos', 0);
    }

    public function test_update_saves_a_percentage_promo(): void
    {
        $this->travelTo('2026-11-25 12:00:00');
        $promo = $this->createPromo();

        $this->withSession($this->adminSession())
            ->put(route('promo.update', $promo), $this->promoInput(['nama_promo' => 'Promo Diperbarui']))
            ->assertRedirect(route('promo.index'));

        $this->assertDatabaseHas('promos', [
            'id' => $promo->id,
            'nama_promo' => 'Promo Diperbarui',
            'jenis_diskon' => 'Persentase',
        ]);
    }

    public function test_update_rejects_a_nominal_promo_without_changing_the_existing_record(): void
    {
        $this->travelTo('2026-11-25 12:00:00');
        $promo = $this->createPromo();

        $this->from(route('promo.edit', $promo))
            ->withSession($this->adminSession())
            ->put(
                route('promo.update', $promo),
                $this->promoInput(['jenis_diskon' => 'Nominal'])
            )
            ->assertRedirect(route('promo.edit', $promo))
            ->assertSessionHasErrors('jenis_diskon');

        $this->assertDatabaseHas('promos', [
            'id' => $promo->id,
            'nama_promo' => 'Promo Lama',
            'jenis_diskon' => 'Persentase',
        ]);
    }

    /**
     * @return array<string, int|string|bool>
     */
    private function adminSession(): array
    {
        return [
            'admin_id' => 1,
            'admin_pool_id' => 'pool-test',
            'admin_must_change_password' => false,
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function promoInput(array $overrides = []): array
    {
        return array_merge([
            'nama_promo' => 'Promo Baru',
            'deskripsi' => 'Diskon untuk pengunjung',
            'jenis_diskon' => 'Persentase',
            'nilai_diskon' => 20,
            'tanggal_mulai' => '2026-11-26',
            'tanggal_selesai' => '2026-12-01',
        ], $overrides);
    }

    private function createPromo(): Promo
    {
        return Promo::create([
            'pool_id' => 'pool-test',
            'nama_promo' => 'Promo Lama',
            'deskripsi' => 'Diskon sebelumnya',
            'jenis_diskon' => 'Persentase',
            'nilai_diskon' => 15,
            'tanggal_mulai' => '2026-11-26',
            'tanggal_selesai' => '2026-12-01',
            'status' => true,
        ]);
    }
}
