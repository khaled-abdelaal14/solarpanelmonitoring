<?php

namespace Database\Seeders;
use App\Models\{Battery, Device, Sensor, Panel, SensorReading, BatteryReading, PanelReading};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::factory(1)->create();
         \App\Models\User::factory(1)->create();
        Device::factory()->count(1)->create();
        Battery::factory()->count(1)->create();
        Sensor::factory()->count(6)->create();
        Panel::factory()->count(1)->create();
        SensorReading::factory()->count(6)->create();
        BatteryReading::factory()->count(6)->create();
        PanelReading::factory()->count(6)->create();
        $this->call(subdeviceSeeder::class);
    }
}
