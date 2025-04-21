<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePanelRequest;
use App\Http\Resources\PanelResource;
use App\Models\Panel;
use App\Models\PanelReading;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PanelController extends Controller
{
  
    public function index(Request $request)
    {
       //hh
        //  $iddevice=User::find($request->user_id)->device()->first()->id;
        //  $panels=Panel::where('device_id',$iddevice)->with('panelReadings')->get();
        //  return response()->json(['panels'=>$panels],200);
        $user = User::find(auth()->user()->id);
        $device = $user->device()->first();

        if ($device) {
            $iddevice = $device->id;
            
        } else {
            return response()->json(['error' => 'المستخدم ليس لديه جهاز  '], 404);
        }

        $panels=Panel::where('device_id',$iddevice)->with('panelReadings')->get();
        if($panels->isEmpty()){
            return response()->json(['error' => 'الجهاز ليس لديه قراءات للالواح '], 404);
        }
        return response()->json(['panels'=>$panels],200);
        // return PanelResource::collection(Panel::select('id','model','capacity','status')->get());
    }

    public function store(StorePanelRequest $request)
    {
        $panel=Panel::create($request->validated());
        return new PanelResource($panel);
    }


    public function show(Panel $panel)
    {
        if(!$panel){
            abort(404,['message'=>'Panel Not Found']);
        }else{
            return new PanelResource($panel);
        }
    }


    public function update(StorePanelRequest $request, Panel $panel)
    {
        if(!$panel){
            abort(404,message:'panel not found');
        }else{
            $panel->update($request->validated());
            return new PanelResource($panel);
        }
    }

   
    public function destroy(Panel $panel)
    {
        //
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

        $panelid = Panel::where('device_id', $iddevice)->pluck('id')->first();
        if(!$panelid){
            return response()->json(['error' => 'الجهاز ليس لديه قراءات للالواح '], 404);
        }



        $lastReading = PanelReading::where('panel_id', $panelid)
                        ->orderBy('created_at', 'desc')
                        ->first();
        
        $panelcapacity = Panel::where('device_id', $iddevice)->pluck('capacity')->first();
        $panelChargelevel=round(($lastReading->energy_stored / $panelcapacity) * 100);;
        
         //average today               
        $day = now()->startOfDay();
        $endday= now()->endOfDay();
        $today = PanelReading::where('panel_id', $panelid)
                    ->whereBetween('created_at', [$day, $endday])
                    ->sum('energy_stored');
    
            
        
        //week
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $thisweek = PanelReading::where('panel_id', $panelid)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('energy_stored');

        //month        
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $thismonth = PanelReading::where('panel_id', $panelid)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('energy_stored'); 
                    
         return response()->json([
            'panelchargelevel'=>$panelChargelevel,
            'lastread'=>$lastReading ? round($lastReading->energy_stored/1000,2) : 0,
            'today'=>$today ? round($today/1000, 2) : 0,
            'thisweek'=>$thisweek ? round($thisweek/1000, 2) : 0,
            'thismonth'=>$thismonth ? round($thismonth/1000, 2)  : 0,
         ],200);           
         
        
    }
}
