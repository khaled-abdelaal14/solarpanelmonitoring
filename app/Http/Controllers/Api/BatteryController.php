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

        $batteryIds = Battery::where('device_id', $iddevice)->pluck('id');
        if(!$batteryIds){
            return response()->json(['error' => 'الجهاز ليس لديه بطارية '], 404);
        }

        $lastReading = BatteryReading::whereIn('battery_id', $batteryIds)
                    ->orderBy('created_at', 'desc')
                    ->first();
                        
        
                        
         // today               
        $day = now()->startOfDay();
        $endOfDay = now()->endOfDay();
        $today = BatteryReading::whereIn('battery_id', $batteryIds)
        ->whereBetween('created_at', [$day, $endOfDay])
        ->sum('energy_stored');
    
            
        
        //week
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $thisWeek = BatteryReading::whereIn('battery_id', $batteryIds)
        ->whereBetween('created_at', [$weekStart, $weekEnd])
        ->sum('energy_stored');

        //month        
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $thisMonth = BatteryReading::whereIn('battery_id', $batteryIds)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->sum('energy_stored');
                    
         return response()->json([
            'lastread'=>$lastReading ? round($lastReading->energy_stored/1000,2) : 0,
            'chage_level'=>$lastReading ? $lastReading->charge_level : 0,
            'today'=>$today ? round($today/1000, 2) : 0,
            'thisweek'=>$thisWeek ? round($thisWeek/1000, 2) : 0,
            'thismonth'=>$thisMonth ? round($thisMonth/1000, 2)  : 0,
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
        $endOfDay = now()->endOfDay();
        $today = BatteryReading::where('battery_id', $batteryid)
                    ->whereBetween('created_at', [$day, $endOfDay])
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

    public function energyConsumedToday(){

        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if (!$device) {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز'], 404);
        }

        $iddevice = $device->id;

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if (!$batteryid) {
            return response()->json(['error' => 'الجهاز ليس لديه بطارية'], 404);
        }

        $startOfDay = now()->startOfDay(); 
        $endOfDay = now()->endOfDay();     

       
        $firstReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'asc')
            ->pluck('energy_stored')
            ->first();

       
        $lastReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'desc')
            ->pluck('energy_stored')
            ->first();

   
        if (is_null($firstReading) || is_null($lastReading)) {
            return response()->json(['error' => 'لا توجد قراءات اليوم'], 404);
        }

        $energyConsumed = $firstReading - $lastReading;

        if ($energyConsumed < 0) {
            $energyConsumed = 0;
        }
        
        return response()->json(['energy_consumed_today' => $energyConsumed], 200);
    }

    public function energyConsumedThisMonth(){

        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if (!$device) {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز'], 404);
        }

        $iddevice = $device->id;

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if (!$batteryid) {
            return response()->json(['error' => 'الجهاز ليس لديه بطارية'], 404);
        }

        $startOfMonth = now()->startOfMonth(); 
        $endOfMonth = now()->endOfMonth();   

   
        $firstReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('created_at', 'asc')
            ->pluck('energy_stored')
            ->first();

      
        $lastReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('created_at', 'desc')
            ->pluck('energy_stored')
            ->first();

       
        if (is_null($firstReading) || is_null($lastReading)) {
            return response()->json(['error' => 'لا توجد قراءات لهذا الشهر'], 404);
        }

        $energyConsumed = $firstReading - $lastReading;

        if ($energyConsumed < 0) {
            $energyConsumed = 0;
        }
        
        return response()->json(['energy_consumed_thismonth' => $energyConsumed], 200);
    }

    public function energyConsumedThisWeek(){

        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if (!$device) {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز'], 404);
        }

        $iddevice = $device->id;

        $batteryid = Battery::where('device_id', $iddevice)->pluck('id')->first();
        if (!$batteryid) {
            return response()->json(['error' => 'الجهاز ليس لديه بطارية'], 404);
        }

        $startOfWeek = now()->startOfWeek(); 
        $endOfWeek = now()->endOfWeek();     

      
        $firstReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'asc')
            ->pluck('energy_stored')
            ->first();

      
        $lastReading = BatteryReading::where('battery_id', $batteryid)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->pluck('energy_stored')
            ->first();

       
        if (is_null($firstReading) || is_null($lastReading)) {
            return response()->json(['error' => 'لا توجد قراءات لهذا الأسبوع'], 404);
        }

        
        $energyConsumed = $firstReading - $lastReading;

        if ($energyConsumed < 0) {
            $energyConsumed = 0;
        }
        
        return response()->json(['energy_consumed_thisweek' => $energyConsumed], 200);
    }


    
}
