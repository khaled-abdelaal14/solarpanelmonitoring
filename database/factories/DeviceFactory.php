<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
class DeviceFactory extends Factory
{
    protected $model =Device::class;
    public function definition()
    {
        return [
            'serial_number' => $this->faker->uuid,
            'status' => $this->faker->randomElement(['on', 'off']),
            'ip_address' => $this->faker->ipv4,
            'user_id' => User::inRandomOrder()->first()->id ,
        ];
    }
}
