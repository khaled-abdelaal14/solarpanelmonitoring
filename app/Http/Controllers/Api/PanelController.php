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
       
         $iddevice=User::find($request->user_id)->device()->first()->id;
         $panels=Panel::where('device_id',$iddevice)->with('panelReadings')->get();
         return response()->json(['panels'=>$panels],200);
        // $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        // $panels=Panel::where('device_id',$iddevice)->with('panelReadings')->get();
        // return response()->json(['panels'=>$panels],200);
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
        $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        $panelid = Panel::where('device_id', $iddevice)->pluck('id')->first();
        $lastReading = PanelReading::where('panel_id', $panelid)
                        ->orderBy('created_at', 'desc')
                        ->first();
        
                        
         //average today               
        $day = now()->startOfDay();
        $today = PanelReading::where('panel_id', $panelid)
                    ->where('created_at', '>=', $day)
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
            'lastread'=>$lastReading ? number_format($lastReading->energy_stored/1000,2).' KW' : 0,
            'today'=>$today ? number_format($today/1000, 2) .' KW': 0,
            'thisweek'=>$thisweek ? number_format($thisweek/1000, 2) .' KW': 0,
            'thismonth'=>$thismonth ? number_format($thismonth/1000, 2) .'KW' : 0,
         ],200);           
         
        
    }
}