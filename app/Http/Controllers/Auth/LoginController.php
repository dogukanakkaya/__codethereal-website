<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->get('remember_me') === 'on';

        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed, redirect to admin
            return redirect()->intended('admin');
        }
        return back()->withErrors([
            'errors' => [__('auth.login_failed')]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
