<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AuthorizeDeviceMail;
use App\Models\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthorizeController extends Controller
{
    public function index()
    {
        return view('auth.authorize');
    }

    public function verify($token)
    {
        if (Authorize::validateToken($token)) {
            return redirect()->route('admin.home');
        }
        return redirect()->route('login')->withErrors(['token' => __('auth.authorize_token_expired')]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if (!Authorize::active() && auth()->check()) {
            $authorize = Authorize::make();

            Mail::to($request->user())->send(new AuthorizeDeviceMail($authorize));

            $authorize->increment('attempt');

            return redirect()->route('authorize')->with(['status' => __('auth.new_email_sent')]);
        }
    }
}
