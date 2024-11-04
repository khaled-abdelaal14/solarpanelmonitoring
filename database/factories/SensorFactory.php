<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['temperature', 'humidity']),
            'device_id' => Device::inRandomOrder()->first()->id,
        ];
    }
}
