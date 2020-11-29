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

    public function verify($token = null)
    {
        if (Authorize::validateToken($token)) {
            return redirect()->route('admin.home')->with([
                'status' => 'Awesome ! you are now authorized !',
            ]);
        }

        return redirect()->route('login')->withErrors(['token' => 'Time expired for token!']);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if (Authorize::inactive() && auth()->check()) {
            $authorize = Authorize::make()->resetAttempt();

            Mail::to($request->user())
                ->send(new AuthorizeDeviceMail($authorize));

            $authorize->increment('attempt');

            return redirect()->route('authorize')->with([
                'status' => __('auth.new_email_sent'),
            ]);
        }
    }
}
