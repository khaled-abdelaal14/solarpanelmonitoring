<?php

use App\Http\Controllers\Api\BatteryController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\PanelController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\SubdeviceController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'user/'],function(){

Route::prefix('auth')->group(function () {
    Route::post('/login',[UserController::class,'login']);
    Route::post('/register', [UserController::class,'register']);
    Route::post('/logout',[UserController::class, 'logout']);
    Route::post('/refresh',[UserController::class, 'refresh']);

});

Route::middleware('auth:user')->group(function(){
    Route::get('device',[DeviceController::class,'index']);
    Route::get('battery',[BatteryController::class,'index']);
    Route::get('battery/energyreading',[BatteryController::class,'energyreading']);
    Route::get('sensor',[SensorController::class,'index']);
    Route::post('panel',[PanelController::class,'index']);
    Route::get('panel/energyreading',[PanelController::class,'energyreading']);
    Route::get('subdevices',[SubdeviceController::class,'index']);
    Route::post('subdevices/calcenergy',[SubdeviceController::class,'calcSubdeviceEnergy']);

});

});

Route::get('/panels',[PanelController::class,'index']);
Route::post('/panels',[PanelController::class,'store']);
Route::put('panels/{panel}',[PanelController::class,'update']);

//chat static
Route::post('/chat/ask', [ChatController::class, 'addask']);
Route::post('/chat', [ChatController::class, 'ask']);

//chat firebase
Route::post('/ask', [ChatController::class, 'detectIntent']);
Route::post('/addintent', [ChatController::class, 'addIntent']);

//chat gemeni
Route::post('/askk', [ChatController::class, 'askQuestion']);



