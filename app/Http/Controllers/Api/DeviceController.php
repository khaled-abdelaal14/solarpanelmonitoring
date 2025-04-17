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
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class DeviceController extends Controller
{

    private $messaging;
    
    public function __construct()
    {
        // Initialize Firebase
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-credentials.json'))
            ->withProjectId('final-project-011x');
            
        $this->messaging = $factory->createMessaging();
    }
    

    
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
            'serial_number' => 'required',
            'sensorname' => 'required',
            'sensortype' => 'required',
            'sensorvalue' => 'required|numeric',
            'battery_serial_number' => 'required',
            'battery_capacity' => 'required|numeric',
            'battery_energy_stored' => 'required|numeric',
            'battery_charge_level' => 'required|numeric',
            'panel_model' => 'required',
            'panel_capacity' => 'required|numeric',
            'panel_energy_produced' => 'required|numeric',
            'status' => 'required|in:on,off'
        ]);
        $serial_number = $request->serial_number;
        $device = Device::where('serial_number', $serial_number)->pluck('id')->first();
        $userDevice = Device::with('user')->where('serial_number', $serial_number)->first();
        
        

        if(!$userDevice){
            return response()->json(['message' => 'Device not found'], 404);
        }else{
            
            $device_token = $userDevice->user->fcm_token;

        }
        
        try{
            DB::beginTransaction();

            Device::where('serial_number', $serial_number)->update([
                'status' => $request->status
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
                'model' => $request->panel_model,
                'capacity' => $request->panel_capacity,
                'status' => 1,
                'device_id' => $device
            ]);
            $panelReading = PanelReading::create([
                'panel_id' => $panel->id,
                'energy_stored' => $request->panel_energy_produced,

            ]);

            //notfication
            if($request->battery_charge_level<25){
                $message = CloudMessage::withTarget('token', $device_token)
                    ->withNotification(Notification::create(
                        'Battery Level Alert',
                        "Battery level is critically low: {$request->battery_charge_level}%"
                    ))
                    ->withData(['battery_level' => (string)$request->battery_charge_level]);
                
                // Send notification
                $this->messaging->send($message);
                $alertbattery = 'Battery level is critically low: '.$request->battery_charge_level.'%';
                Alert::create([
                    'device_id' => $device,
                    'message' => $alertbattery,
                    'alert_type' => 'Battery Level Alert'
                    
                ]);

                if($request->sensorvalue>50){
                    $messagesensor = CloudMessage::withTarget('token', $device_token)
                    ->withNotification(Notification::create(
                        'Sensor Alert',
                        "Sensor value is critically high: {$request->sensorvalue}"
                    ))
                    ->withData(['sensor_value' => (string)$request->sensorvalue]);
                    //send notification
                    $this->messaging->send($messagesensor);
                    $alertsensor = 'Sensor value is critically high: '.$request->sensorvalue;
                    Alert::create([
                        'device_id' => $device,
                        'message' => $alertsensor,
                        'alert_type' => 'Sensor Alert'
                        
                    ]);
                    
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Stored successfully',
                'alertbattery' => $alertbattery??'no alert battery',
                'alertsensor' => $alertsensor??'no alert sensor'
            ], 200);

            

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to store ',
                'error' => $e->getMessage()
            ], 500);
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

    public function getUserNotification(){
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }
        $notification = Alert::where('device_id', $iddevice)->get();
        return response()->json(['notification'=>$notification],200);
    }

    
}
