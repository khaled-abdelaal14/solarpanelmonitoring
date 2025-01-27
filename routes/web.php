<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\ChatController;
use App\Mail\Otpmail;
use Illuminate\Support\Facades\Mail;
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

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('testmail',function(){
//  Mail::to('khaledabdelaala2@gmail.com')->send(new Otpmail(1234));
// });


Route::middleware('guest')->group(function () {
    Route::get('login',[LoginController::class,'login'])->name('login');
    Route::post('login-admin',[LoginController::class,'adminlogin'])->name('login.admin');
   
});


Route::prefix('/admin')->group(function(){
    Route::group(['middleware'=>['auth:admin']],function(){
        Route::post('/logout',[LoginController::class,'logout'])->name('admin.logout');
        Route::get('/dashboard',[AdminController::class,'dashboard']);
        Route::match(['get','post'],'update-details',[AdminController::class,'updatedetails']);
        Route::match(['get','post'],'update-password',[AdminController::class,'updatepassword']);
        Route::post('/check-admin-password',[AdminController::class,'checkcurrentpassword']);

        //admins
        Route::get('admins',[AdminController::class,'admins']);
        Route::match(['get','post'],'add-edit-admin/{id?}',[AdminController::class,'addedit']);
        Route::get('delete-admin/{id}',[AdminController::class,'deleteadmin']);
        Route::match(['get','post'],'update-role/{id}',[AdminController::class,'updaterole']);


        //users
        Route::get('users',[AdminController::class,'showusers']);
        Route::match(['get','post'],'add-edit-user/{id?}',[AdminController::class,'addedituser']);

        Route::get('delete-user/{id}',[AdminController::class,'deleteuser']);

        //devices
        Route::get('devices',[AdminController::class,'showdevices']);
        Route::match(['get','post'],'add-edit-device/{id?}',[AdminController::class,'addeditdevice']);

        Route::get('delete-device/{id}',[AdminController::class,'deletedevice']);


        

    });
});



