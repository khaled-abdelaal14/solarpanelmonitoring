<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Otpmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPawword extends Controller
{
        public function sendResetCode(Request $request){

        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = DB::table('users')->where('email', $request->email)->first();

        $code = random_int(1000, 9999);

        DB::table('password_resets')->Insert([
            'email' => $request->email,
            'token' => $code,
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new Otpmail($code));


        return response()->json(['message' => 'Reset code sent to your email.'], 200);
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required'
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset ||Carbon::parse($passwordReset->created_at)->addMinutes(30) < Carbon::now()) {
            return response()->json(['message' => 'Invalid or expired reset code.'], 400);
        }

        return response()->json(['message' => 'Reset code is valid.','user info'=> $passwordReset], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset || Carbon::parse($passwordReset->created_at)->addMinutes(30) < Carbon::now()) {
            return response()->json(['message' => 'Invalid or expired reset code.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)
                            ->where('token', $request->token)
                            ->delete();

        return response()->json(['message' => 'Password has been reset successfully.'], 200);
    }


}
