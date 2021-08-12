<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

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

    protected function authenticated($request, $user){

        if(!$user->email_verified_at){
          return $this->forceLogout('Email Belum Terverifikasi');
        }

        if(!($user->role == 'admin' || $user->role == 'supplier')) {
           return $this->logoutWithError($user->email);
        }
    }

    private function forceLogout($error) {
        auth()->logout();
        return redirect()->route('main-login')->with('error', $error);
    }

    private function logoutWithError($email) {
        auth()->logout();
        return redirect()->route('main-login')->with('error-role', 
        ['email' => $email, 'message' => 'These credentials do not match our records.']);
    }
}
