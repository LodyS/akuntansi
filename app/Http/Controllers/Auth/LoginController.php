<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/beranda';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username ()
    {
      return 'username';
    }

      protected function credentials(Request $request)
    {
        // return $request->only($this->username(), 'password');
        return array_merge($request->only($this->username(), 'password'), ['status_aktif' => 'Y']);
    }

     public function authenticated(Request $request, $user)
    {
        if(!$user->verified)
        {
            auth()->logout();
            message(false,'','Silahkan anda mengecek Email anda untuk mengkonfirmasi akun anda!');
            // return back()->with('warning','You need to confirm your account. We have sent you an activation code, please check your email.');
            return back();

        }
        return redirect()->intended($this->redirectPath());
    }
    
}
