<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Tariff;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_latitude = $this->faker->latitude($min = -90, $max = 90);
        $start_longitude = $this->faker->latitude($min = -90, $max = 90);
        $tarrif=Tariff::factory()->create();
        return [
            'start_time' => Carbon::now(),
            'start_latitude' => $start_latitude,
            'start_longitude'=> $start_longitude,
            'fix_rate' => $tarrif->fix_rate,
            'rate_per_km' => $tarrif->rate_per_km,
            'rate_per_minute' => $tarrif->rate_per_minute,
            'last_update_time' => Carbon::now(), 
            'last_latitude' => $start_latitude,
            'last_longitude'=> $start_longitude, 
            'total_tarrif' => 0.00,
            'distance_tarrif' => 0.00,
            'waiting_tarrif' => 0.00,
            'ride_distance' => 0.00,
            'ride_speed' => 0.00,
            'total_waiting_time'=>0
        ];
         //  'last_update_time' => Carbon::now(), ->subSeconds( 10 ) ->subHours( 1 )
    }
}
