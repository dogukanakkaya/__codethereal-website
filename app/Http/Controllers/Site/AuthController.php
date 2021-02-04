<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:10,60')->only('login', 'register', 'updateProfile');
        $this->middleware('authorize')->only('login', 'profile');
        $this->middleware('guest')->except('profile', 'updateProfile');
        $this->middleware('auth')->only('profile', 'updateProfile');
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
        $user = User::where('email', request('email'))->first();
        if ($user?->hasVerifiedEmail()){
            if (Auth::attempt($credentials, (bool)$request->get('remember_me'))) {
                $request->session()->regenerate();
                return resJson(1, ['message' => __('auth.wait_for_redirect')]);
            }
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

    public function profile()
    {
        return view('site.pages.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = User::find(auth()->id());
        $data = $request->validated();
        $currentPassword = array_remove($data, 'current_password');
        if (Hash::check($currentPassword, $user->password)){
            return resJson(User::where('id', auth()->id())->update($data));
        }else{
            return resJson(0, ['message' => __('site.auth.profile_update_password_incorrect')]);
        }
    }
}
