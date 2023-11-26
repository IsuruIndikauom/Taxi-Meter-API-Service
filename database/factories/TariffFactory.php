<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarrif>
 */
class TariffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fix_rate' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100),
            'rate_per_km' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100),
            'rate_per_minute' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100),  
            'status' => 1,
        ];
    }
}
