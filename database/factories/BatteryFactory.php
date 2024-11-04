<?php

namespace Database\Factories;

use App\Models\Battery;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatteryFactory extends Factory
{
    protected $model = Battery::class;
    public function definition()
    {
        return [
            'serial_number' => $this->faker->uuid,
            'device_id' => Device::inRandomOrder()->first()->id,
            'capacity' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
