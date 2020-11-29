<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('authorize')->only('login');
        $this->middleware('guest')->except('logout');
    }

    /**
     * Required credentials for login
     *
     * @return array
     */
    protected function credentials()
    {
        return [
            'email' => request('email'),
            'password' => request('password'),
            //'remember_me' => request('remember_me'),
            'rank' => [config('user.rank.dev'), config('user.rank.admin')] // Only developers and admins can login to admin
        ];
    }
}
