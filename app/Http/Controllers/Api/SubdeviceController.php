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
        $device = Subsubdevice::where('id', $request->subsubdevice_id)->first();

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
            'total_consumption_per_hour' => $totalconsumptionperHour . '  كيلو واط',
            'battery_energy' => $lastReading->energy_stored . ' كيلوواط/ساعة',
            'percentage_used' => number_format($percentageUsed, 2) . '%',
            'used_energy' => number_format($usedEnergy, 2) . 'كيلو واط',
            'sufficient_energy' => $sufficientEnergy ? 'نعم' : 'لا',
            
            
        ]);


     }
}
