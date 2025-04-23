<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use App\Models\BatteryReading;
use App\Models\Subdevice;
use App\Models\Subsubdevice;
use App\Models\User;
use Illuminate\Http\Request;

class SubdeviceController extends Controller
{
    public function index(){
        $subdevices=Subdevice::with('subsubdevices')->get();
        return response()->json(['subdevices'=>$subdevices],200);
    }

    public function calcSubdeviceEnergy(Request $request){
        $request->validate([
            'subsubdevice_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        if($request->subsubdevicename){
            $device=Subsubdevice::create([
                'name'=>$request->subsubdevicename,
                'watt_per_hour'=>$request->watt_per_hour,
                'subdevice_id'=>$request->subdevice_id,
            ]);
            $message='SubSubdevice added successfully and reading calculated';
        }
        else{
            $device = Subsubdevice::where('id', $request->subsubdevice_id)->first();
            $message='SubSubdevice reading calculated successfully';
        }
        

        if (!$device) {
            return response()->json('error', 'الجهاز غير موجود في  .');
        }    
        $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        $lastReading = BatteryReading::where('battery_id', $batteryid)
                        ->orderBy('created_at', 'desc')
                        ->first();

        if (!$lastReading) {
        return response()->json(['error' => 'لا توجد قراءات بطارية متاحة.'], 404);
                    }

        $totalconsumptionperHour = $request->quantity * ($device->watt_per_hour/1000);
        $percentageUsed = ($totalconsumptionperHour / ($lastReading->energy_stored )) * 100;
        $sufficientEnergy = $lastReading->energy_stored >= $totalconsumptionperHour;
        $usedEnergy = ($percentageUsed / 100) * $lastReading->energy_stored;

        return response()->json([
            'message' => $message,
            'total_consumption_per_hour' => $totalconsumptionperHour . '  كيلو واط',
            'battery_energy' => $lastReading->energy_stored . ' كيلوواط/ساعة',
            'percentage_used' => number_format($percentageUsed, 2) . '%',
            'used_energy' => number_format($usedEnergy, 2) . 'كيلو واط',
            'sufficient_energy' => $sufficientEnergy ? 'نعم' : 'لا',
            
            
        ]);


     }

     public function addsubsubDevice(Request $request){
        $request->validate([
            'name' => 'required|string',
            'watt_per_hour' => 'required|integer',
            'subdevice_id' => 'required|integer|exists:subdevices,id',
        ]);

        $device=Subsubdevice::create($request->all());
        return response()->json([
            'message'=>'device added successfully',
            'device'=>$device
        ],200);
    }

    public function updateSubSubDeviceStatus(Request $request){
        $request->validate([
            'status' => 'required|in:on,off',
            'subsubdevice_id' => 'required|integer',
        ]);

        $device=Subsubdevice::where('id',$request->subsubdevice_id)->first();
        if(!$device){
            return response()->json(['error'=>'device not found'],404);
        }
        $device->update([
            'status'=>$request->status
        ]);
        return response()->json([
            'message'=>'device status updated successfully',
            'device'=>$device
        ],200);
    }
    public function updateSubSubDeviceHasDevice(Request $request){
        $request->validate([
            'has_device' => 'required|in:yes,no',
            'subsubdevice_id' => 'required|integer',
        ]);

        $device=Subsubdevice::where('id',$request->subsubdevice_id)->first();
        if(!$device){
            return response()->json(['error'=>'device not found'],404);
        }
        $device->update([
            'has_device'=>$request->has_device
        ]);
        return response()->json([
            'message'=>'device has_device updated successfully',
            'device'=>$device
        ],200);
    }

    public function getSubSubDevices(){
        $SubSubDevices=Subsubdevice::where('has_device','yes')->get();
        if($SubSubDevices->isEmpty()){
            return response()->json(['message'=>'No devices for User'], 404);
        }
        return response()->json([
            'message'=>'SubSubDevices for User',
            'SubSubDevices'=>$SubSubDevices
        ],200);

    }
}
