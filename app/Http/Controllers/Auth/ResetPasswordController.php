<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    public function getPassword(Request $request)
    {
        $user = PasswordReset::where(['token' => $request->token])->first();
        if ($user) return view('otp', ['token' => $request->token]);

        return redirect('/login')->with('error', 'Invalid token!');
    }


    public function updatePassword(Request $request)
    {
        $token = $request->only('token');

        $request->validate([
            'token' => 'required',
            'password' => 'required|string|confirmed'
        ]);

        $user = PasswordReset::where(['token' => $token])->first();
        if (!$user) return back()->withInput()->with('error', 'Invalid token!');

        User::where('email', $user->email)->update(['password' => Hash::make($request->password)]);
        PasswordReset::where(['email' => $user->email, 'token' => $token])->delete();

        return redirect('/login')->with('status', 'Your password has been changed!');
    }
}
