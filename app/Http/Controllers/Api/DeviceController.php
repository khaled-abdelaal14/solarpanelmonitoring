<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use App\Models\BatteryReading;
use App\Models\Device;
use App\Models\Panel;
use App\Models\PanelReading;
use App\Models\Sensor;
use App\Models\SensorReading;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function index(){
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            return response()->json(['device'=>$device],200);
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز '], 404);
        }
  
        
    }

    public function toggleDeviceStatus(Request $request){
 
        if($request->status=="off"){
            $status='off';
            $message = 'Device turned off and notified IoT';
        }else{
            $status='on';
            $message = 'Device turned on and notified IoT';

        }

        $device = Device::where('serial_number', $request->serial_number)->first();
        if ($device) {
            $device->status = $status;
            $device->save();

            return response()->json(['message' => $message], 200);
        }

        return response()->json(['message' => 'Device not found'], 404);
    }

    public function store(Request $request){
        $request->validate([
            'serial_number' => 'required|unique:devices,serial_number',
            'sensorname' => 'required',
            'sensortype' => 'required',
            'sensorvalue' => 'required|numeric',
            'battery_serial_number' => 'required|unique:batteries,serial_number',
            'battery_capacity' => 'required|numeric',
            'battery_energy_stored' => 'required|numeric',
            'battery_charge_level' => 'required|numeric',
            'panel_model' => 'required',
            'panel_capacity' => 'required|numeric',
            'panel_energy_produced' => 'required|numeric',
        ]);
        $serial_number = $request->serial_number;
        $device = Device::where('serial_number', $serial_number)->pluck('id')->first();
        if(!$device){
            return response()->json(['message' => 'Device not found'], 404);
        }
        try{
            DB::beginTransaction();

            Device::where('serial_number', $serial_number)->update([
                'status' => 'on'
            ]);

            //sensor
            $sensor = Sensor::updateorCreate([
                'name' => $request->sensorname,
                'type' => $request->sensortype,
                'device_id' => $device
            ]);
           
            $senorReadings=SensorReading::create([
                'sensor_id' => $sensor->id,
                'value' => $request->sensorvalue
            ]);

            // battery
            $battery = Battery::updateorCreate([
                'serial_number' => $request->battery_serial_number,
                'capacity' => $request->battery_capacity,
                'device_id' => $device
            ]);
            $batteryReading = BatteryReading::create([
                'battery_id' => $battery->id,
                'energy_stored' => $request->battery_energy_stored,
                'charge_level' => $request->battery_charge_level,
            ]);

            //panel
            $panel = Panel::updateorCreate([
                'serial_number' => $request->panel_model,
                'capacity' => $request->panel_capacity,
                'status' => 1,
                'device_id' => $device
            ]);
            $panelReading = PanelReading::create([
                'panel_id' => $panel->id,
                'energy_stored' => $request->panel_energy_produced,

            ]);

            DB::commit();
            return response()->json(['message' => 'Stored successfully'], 200);

            

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to store '], 500);
        }

        //

    }

    
    // $data=[
    //     "serial_number": "12345",
    //     "sensorname": "Temperature Sensor",
    //     "sensortype": "temperature",
    //     "sensorvalue": "35",
    //     "battery_serial_number": "BAT001",
    //     "battery_capacity": "2000",
    //     "battery_energy_stored": "1500",
    //     "battery_charge_level": "75",
    //     "panel_model": "PNL001",
    //     "panel_capacity": "300",
    //     "panel_energy_produced": "250"
    // ];

    // getDeviceStatus
    public function DeviceStatus($serial_number){
        $device = Device::where('serial_number', $serial_number)->first();
        if ($device) {
            return response()->json(['status' => $device->status]);
        }
        return response()->json(['message' => 'Device not found'], 404);
    }

    //iot 
    public function iotChangeStatus(Request $request){
        $device = Device::where('serial_number', $request->serial_number)->first();
        if ($device) {
            $device->status = $request->status;
            $device->save();
            return response()->json(['message' => 'Device status changed successfully'], 200);
        }
        return response()->json(['message' => 'Device not found'], 404);
    }

    
}
