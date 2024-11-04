<?php

namespace Database\Seeders;

use App\Models\Subdevice;
use App\Models\Subsubdevice;
use Illuminate\Database\Seeder;

class subdeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $subDevices = [
        //     ['id'=>1,'name' => 'LED Bulb'],
        //     ['id'=>2,'name' => 'Incandescent Bulb' ],
        //     ['id'=>3,'name' => 'Refrigerator' ],
        //     ['id'=>4,'name' => 'Fan' ],
        //     ['id'=>5,'name' => 'Television' ],
        //     ['id'=>6,'name' => 'Desktop Computer'],
        //     ['id'=>7,'name' => 'Laptop'],
        //     ['id'=>8,'name' => 'Water Heater'],
        //     ['id'=>9,'name' => 'Washing Machine'],
        //     ['id'=>10,'name' => 'Microwave'],
        //     ['id'=>11,'name' => 'Portable Air Conditioner'],
        //     ['id'=>12,'name' => 'Fixed Air Conditioner'],
        //     ['id'=>13,'name' => 'Electric Heater'],
        //     ['id'=>14,'name' => 'Hair Dryer'],
        //     ['id'=>15,'name' => 'Coffee Maker'],
        // ];
 

        
        // $subSubDevices = [
            //     ['name' => 'Smart LED TV', 'watt_per_hour' => 75, 'subsubdeviceid' => 5],
            //     ['name' => 'Laptop', 'watt_per_hour' => 65, 'subsubdeviceid' => 15],
            //     ['name' => 'Refrigerator', 'watt_per_hour' => 150, 'subsubdeviceid' => 3],
            //     // يمكنك إضافة المزيد من الأجهزة مع قيم دقيقة
            // ];
            
            //   Subdevice::insert($subDevices);
            //   Subsubdevice::insert($subSubDevices);
            $devices = [
                ['name' => 'Television'],
                ['name' => 'Refrigerator'],
                ['name' => 'Air Conditioner'],
                ['name' => 'Washing Machine'],
                ['name' => 'Laptop'],
                ['name' => 'Microwave'],
               
            ];
                foreach ($devices as $device) {
                SubDevice::create($device);
            }

            $televisionId = SubDevice::where('name', 'Television')->first()->id;
            $refrigeratorId = SubDevice::where('name', 'Refrigerator')->first()->id;
            $airConditionerId = SubDevice::where('name', 'Air Conditioner')->first()->id;
            $washingMachineId = SubDevice::where('name', 'Washing Machine')->first()->id;
            $laptopId = SubDevice::where('name', 'Laptop')->first()->id;
            $microwaveId = SubDevice::where('name', 'Microwave')->first()->id;

            $subSubDevices = [
                ['name' => 'Smart LED TV', 'watt_per_hour' => 80, 'subsubdeviceid' => $televisionId],
                ['name' => '4K LED TV', 'watt_per_hour' => 150, 'subsubdeviceid' => $televisionId],
                ['name' => 'OLED TV', 'watt_per_hour' => 120, 'subsubdeviceid' => $televisionId],
    
                ['name' => 'Single Door Refrigerator', 'watt_per_hour' => 100, 'subsubdeviceid' => $refrigeratorId],
                ['name' => 'Double Door Refrigerator', 'watt_per_hour' => 180, 'subsubdeviceid' => $refrigeratorId],
                ['name' => 'Mini Fridge', 'watt_per_hour' => 70, 'subsubdeviceid' => $refrigeratorId],
    
                ['name' => '1 Ton AC', 'watt_per_hour' => 1500, 'subsubdeviceid' => $airConditionerId],
                ['name' => '1.5 Ton AC', 'watt_per_hour' => 2000, 'subsubdeviceid' => $airConditionerId],
                ['name' => '2 Ton AC', 'watt_per_hour' => 2500, 'subsubdeviceid' => $airConditionerId],
    
                ['name' => 'Front Load Washing Machine', 'watt_per_hour' => 450, 'subsubdeviceid' => $washingMachineId],
                ['name' => 'Top Load Washing Machine', 'watt_per_hour' => 500, 'subsubdeviceid' => $washingMachineId],
    
                ['name' => 'Gaming Laptop', 'watt_per_hour' => 80, 'subsubdeviceid' => $laptopId],
                ['name' => 'Business Laptop', 'watt_per_hour' => 45, 'subsubdeviceid' => $laptopId],
    
                ['name' => 'Convection Microwave', 'watt_per_hour' => 1200, 'subsubdeviceid' => $microwaveId],
                ['name' => 'Grill Microwave', 'watt_per_hour' => 1000, 'subsubdeviceid' => $microwaveId],
            ];

            foreach ($subSubDevices as $subSubDevice) {
                SubSubDevice::create($subSubDevice);
            }

    }
}
