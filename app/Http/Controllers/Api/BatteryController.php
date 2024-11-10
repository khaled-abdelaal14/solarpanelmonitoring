<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use App\Models\BatteryReading;
use App\Models\User;
use Illuminate\Http\Request;

class BatteryController extends Controller
{
    public function index(){
        $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        $batteries=Battery::where('device_id',$iddevice)->with('batteryReadings')->get();
        return response()->json(['baterries'=>$batteries],200);
    }
    public function energyreading()
    {
        $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        $lastReading = BatteryReading::where('battery_id', $batteryid)
                        ->orderBy('created_at', 'desc')
                        ->first();
        
                        
         // today               
        $day = now()->startOfDay();
        $today = BatteryReading::where('battery_id', $batteryid)
                    ->where('created_at', '>=', $day)
                    ->sum('energy_stored');
    
            
        
        //week
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $thisweek = BatteryReading::where('battery_id', $batteryid)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('energy_stored');

        //month        
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $thismonth = BatteryReading::where('battery_id', $batteryid)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('energy_stored'); 
                    
         return response()->json([
            'lastread'=>$lastReading ? number_format($lastReading->energy_stored/1000).' KW' : 0,
            'today'=>$today ? number_format($today/1000, 2) .' KW': 0,
            'thisweek'=>$thisweek ? number_format($thisweek/1000, 2) .' KW': 0,
            'thismonth'=>$thismonth ? number_format($thismonth/1000, 2) .'KW' : 0,
         ],200);           
         
        
    }
    
}
