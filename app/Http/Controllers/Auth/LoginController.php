<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Foundation\Auth\Access\Authorizable;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // overriding Laravel UI Auth sendLoginResponse function()
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        if ($request->has('remember')) {
            $customRememberMeTimeInMinutes = 131400; // 3 months
            $rememberTokenCookieKey = Auth::getRecallerName();
            Cookie::queue($rememberTokenCookieKey, Cookie::get($rememberTokenCookieKey), $customRememberMeTimeInMinutes);
        }

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        $deletedAccount = Employee::join('users', 'employees.user_id', '=', 'users.id')->where('email', '=', $request['email'])->first();

        if ($deletedAccount['date_of_leaving'] != null) {
            $rememberTokenCookieKey = Auth::getRecallerName();
            $cookie = Cookie::forget($rememberTokenCookieKey);

            $request->session()->invalidate();

            $this->guard()->logout();
            $request->session()->regenerateToken();

            return $request->wantsJson() ? new JsonResponse([], 204) : redirect('/login')->withCookie($cookie)->with('message', 'These credentials do not match our records.')->with('error-email', $request['email']);
        } else {
            return $request->wantsJson() ? new JsonResponse([], 204) : redirect()->intended($this->redirectPath());
        }
    }

    public function logout(Request $request)
    {
        $rememberTokenCookieKey = Auth::getRecallerName();
        $cookie = Cookie::forget($rememberTokenCookieKey);

        $request->session()->invalidate();

        $this->guard()->logout();
        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login')->withCookie($cookie);
    }
}
