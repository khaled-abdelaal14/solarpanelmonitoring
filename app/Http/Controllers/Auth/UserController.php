<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['login','register']]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        
        $user=User::where('email',$credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'البريد الإلكتروني غير صحيح',
            ], 401);
        }

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'كلمة المرور غير صحيحة',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'phone'=> 'required|numeric|digits:11|unique:users,phone'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            'city' => $request->city,

        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token saved successfully',
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone'=> 'required|numeric|digits:11|unique:users,phone,'.Auth::id(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
      

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->city = $request->city;

        if ($request->hasFile('image')) {
            $destination = 'user/photos';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
        
            $path = $image->storeAs($destination, $image_name, 'public');
            $user->image = 'storage/' . $path;
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User information updated successfully',
            'user' => $user,
        ]);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Old password is incorrect',
            ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully',
        ]);
    }



}
