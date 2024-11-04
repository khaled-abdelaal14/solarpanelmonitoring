<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(){
        $device=User::find(auth()->user()->id)->device()->get();
  
        return response()->json(['device'=>$device],200);
    }
}
