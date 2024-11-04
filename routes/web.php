<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('chat');
});
Route::get('/chat2',[ChatController::class,'testConnection']);
Route::get('/ask1',[ChatController::class,'ask1']);

Route::get('device',[DeviceController::class,'index']);

// Route::post('/chatbot', [ChatController::class, 'sendMessage']);



