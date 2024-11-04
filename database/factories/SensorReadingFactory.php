<?php

namespace Database\Factories;

use App\Models\Sensor;
use App\Models\SensorReading;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorReadingFactory extends Factory
{
    protected $model = SensorReading::class;

    public function definition()
    {
        return [
            'sensor_id' => Sensor::inRandomOrder()->first()->id,
            'value' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
