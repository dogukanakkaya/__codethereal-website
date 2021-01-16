<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

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

    public function registerView()
    {
        return response()
            ->view('site.auth.register-modal')
            ->header('Content-Type', 'application/html');
    }
}
