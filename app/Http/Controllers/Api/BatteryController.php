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
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }
        
        $batteries=Battery::where('device_id',$iddevice)->with('batteryReadings')->get();
        if($batteries->isEmpty()){
            return response()->json(['error' => 'الجهاز ليس لديه قراءات للبطارية '], 404);
        }else{
            return response()->json(['batteries'=>$batteries],200);
        }
       
    }
    public function energyreading()
    {
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if(!$batteryid){
            return response()->json(['error' => 'الجهاز ليس لديه بطارية '], 404);
        }

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

    public function energyreadingtoday()
    {
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if(!$batteryid){
            return response()->json(['error' => 'الجهاز ليس لديه بطارية '], 404);
        }


        $day = now()->startOfDay();
        $today = BatteryReading::where('battery_id', $batteryid)
                    ->where('created_at', '>=', $day)
                    ->get();
        return response()->json(['today'=>$today],200);
    }

    public function energyreadingweek()
    {
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if(!$batteryid){
            return response()->json(['error' => 'الجهاز ليس لديه بطارية '], 404);
        }
        
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $thisweek = BatteryReading::where('battery_id', $batteryid)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->get();
        return response()->json(['thisweek'=>$thisweek],200);
    }
    
    public function energyreadingmonth()
    {
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if(!$batteryid){
            return response()->json(['error' => 'الجهاز ليس لديه بطارية '], 404);
        }
        
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $thismonth = BatteryReading::where('battery_id', $batteryid)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->get();
        return response()->json(['thismonth'=>$thismonth],200);
    }

    
}
