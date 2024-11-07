<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function adminlogin(Request $request){
        if($request->isMethod('post')){
            
            $rules=[
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];
            $custommessage=[
                'email.required'=>'Email is required',
                'email.email'=>'Email valid!',
                'password.required'=>'password is required'
            ];
            $this->validate($request,$rules,$custommessage);
            if(Auth::guard('admin')->attempt(['email'=>$request->email ,'password'=>$request->password])){
            
                Session::regenerateToken();
                return redirect()->intended(RouteServiceProvider::ADMIN);
            }else{
                return redirect()->back()->with('erorr_message','invalid Email or Password');
            }
        }
    }
    
    public function logout(){
        Auth::guard('admin')->logout();

        Session::invalidate();

       Session::regenerateToken();

        return redirect('/login');
    }
}
