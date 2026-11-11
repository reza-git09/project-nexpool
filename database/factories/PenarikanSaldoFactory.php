<?php

namespace Database\Factories;

use App\Models\PenarikanSaldo;
use App\Models\Pool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenarikanSaldo>
 */
class PenarikanSaldoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pool_id' => fn (array $attributes): string => Pool::factory()->create()->pool_id,
            'bank_name' => fake()->randomElement(['BCA', 'BRI', 'BNI', 'Mandiri']),
            'account_number' => fake()->numerify('################'),
            'amount' => fake()->randomFloat(2, 1, 10000000),
            'status' => PenarikanSaldo::STATUS_PROSES,
            'processed_by' => null,
            'processed_at' => null,
            'rejection_reason' => null,
        ];
    }
}
