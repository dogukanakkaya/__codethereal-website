<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:10,60')->only('login');
        $this->middleware('authorize')->only('login');
        $this->middleware('verified')->only('login');
    }

    public function loginView()
    {
        return response()
            ->view('site.auth.login-modal')
            ->header('Content-Type', 'application/html');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->get('remember_me'))) {
            $request->session()->regenerate();
            return resJson(1, ['message' => __('auth.wait_for_redirect')]);
        }
        return resJson(0, ['message' => __('auth.failed')]);
    }

    public function registerView()
    {
        return response()
            ->view('site.auth.register-modal')
            ->header('Content-Type', 'application/html');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->sendEmailVerificationNotification();
        return resJson($user, [
            'message' => __('auth.registered_needs_verification')
        ]);

    }
}
