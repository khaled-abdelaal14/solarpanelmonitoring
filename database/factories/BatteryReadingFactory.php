<?php

namespace Database\Factories;

use App\Models\Battery;
use App\Models\BatteryReading;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatteryReadingFactory extends Factory
{
    protected $model = BatteryReading::class;

    public function definition()
    {
        return [
            'battery_id' => Battery::inRandomOrder()->first()->id ,
            'energy_stored' => $this->faker->numberBetween(0, 1000),
            'charge_level' => $this->faker->numberBetween(0, 100),
            
        ];
    }
}
