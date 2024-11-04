<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PanelFactory extends Factory
{
    protected $model = Panel::class;

    public function definition()
    {
        return [
            'device_id' => Device::inRandomOrder()->first()->id,
            'capacity' => $this->faker->numberBetween(100, 500),
            'model' => $this->faker->word,
            'status' => $this->faker->randomElement(['0', '1']),
         
        ];
    }
}
