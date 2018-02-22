<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Http\Request;

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
    use LaratrustUserTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function username()
    {
        return 'email';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->confirmed) {
            //event(new UserRegistered($user));
            auth()->logout();
            return back()
                ->with('warning', 'Your account is waiting confirmation. We will let you know when its ready.')
                ->with('email',$request->input('email'));
        }

        return $this->redirectViaRole($user);
    }

    protected function redirectViaRole($user)
    {
        if ($user->hasRole('administrator')) {
            return redirect()->route('admin');
        }

        if ($user->hasRole('employee')) {
            return redirect()->route('employee');
        }

        if ($user->hasRole('client')) {
            return redirect()->route('client');
        }

        return redirect()->route('home');
    }
}
