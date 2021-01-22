<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('authorize')->only('login');
        $this->middleware('guest')->except('logout');
    }

    public function loginView()
    {
        return response()
            ->view('site.auth.login-modal')
            ->header('Content-Type', 'application/html');
    }

    public function login()
    {

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
        return resJson(User::create($data));
    }
}
