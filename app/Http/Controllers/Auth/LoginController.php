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
        $this->middleware('authorized')->only('login');
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
            'rank' => [config('user.rank.dev'), config('user.rank.admin')] // Only developers and admins can login to admin
        ];
    }
}
