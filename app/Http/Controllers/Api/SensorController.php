<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index(){
        $iddevice=User::find(auth()->user()->id)->device()->first()->id;
        $sensors=Sensor::where('device_id',$iddevice)->with('sensorReadings')->get();
        return response()->json(['sensors'=>$sensors],200);
    }
}
