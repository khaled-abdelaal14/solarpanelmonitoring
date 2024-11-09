<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function index(){
        $device=User::find(auth()->user()->id)->device()->get();
  
        return response()->json(['device'=>$device],200);
    }

    public function toggleDeviceStatus(Request $request, $serial_number){
 
        if($request->status=='off'){
            $status='on';
            $message = 'Device turned on and notified IoT';
        }else{
            $status='off';
            $message = 'Device turned off and notified IoT';

        }

        $device = Device::where('serial_number', $serial_number)->first();
        if ($device) {
            $device->status = $status;
            $device->save();

    
            $iotEndpoint = 'http://esp32-device-endpoint/command'; 
            $response = Http::post($iotEndpoint, [
                'serial_number' => $serial_number,
                'action' => $status
            ]);

            // التحقق من الاستجابة وإرجاع النتيجة إلى تطبيق Flutter
            if ($response->successful()) {
                return response()->json(['message' => $message]);
            } else {
                return response()->json(['message' => 'Failed to notify IoT'], 500);
            }
        }

        return response()->json(['message' => 'Device not found'], 404);
    }
}
