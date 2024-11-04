<?php

namespace Database\Factories;

use App\Models\Battery;
use App\Models\BatteryReading;
use App\Models\Panel;
use App\Models\PanelReading;
use Illuminate\Database\Eloquent\Factories\Factory;

class PanelReadingFactory extends Factory
{
    protected $model = PanelReading::class;

    public function definition()
    {
        return [
            'panel_id' => Panel::inRandomOrder()->first()->id ,
            'energy_stored' => $this->faker->numberBetween(0, 1000),
           
            
        ];
    }
}
