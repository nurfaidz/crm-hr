<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Auth;
 
class ChangePasswordController extends Controller
{
 
public function __construct()
{
 $this->middleware('auth');
 $this->middleware('preventBackHistory');
}
 
public function index()
{
return view('home');
}
 
public function showChangePasswordForm(){
return view('auth.ChangePassword');
}
 
public function changePassword(Request $request){
 
if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
// The passwords matches
return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
}
 
if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
//Current password and new password are same
return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
}
if(!(strcmp($request->get('new-password'), $request->get('new-password-confirm'))) == 0){
            //New password and confirm password are not same
            return redirect()->back()->with("error","New Password should be same as your confirmed password. Please retype new password.");
}
//Change Password
$user = Auth::user();
$user->password = bcrypt($request->get('new-password'));
$user->save();
 
return redirect()->back()->with("success","Password changed successfully !");


}
}