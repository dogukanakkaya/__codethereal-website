<?php

namespace App\Http\Middleware;

use App\Mail\AuthorizeDeviceMail;
use App\Models\Authorize;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthorizeDevice
{
    /**
     * @var Authorize
     */
    private Authorize $authorize;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Authorize::active() && auth()->check()) {
            $this->authorize = Authorize::make();

            if ($this->authorize->attempt < 1) {
                Mail::to($request->user())->send(new AuthorizeDeviceMail($this->authorize));
                $this->authorize->increment('attempt');
            }

            if ($this->timeout()) {
                auth()->guard()->logout();
                $request->session()->invalidate();

                return redirect()->route('login')->withErrors(['status' => __('auth.authorize_logged_out', ['sent_before' => 10, 'valid_for' => 1])]);
            }

            // Infinite redirect problem. If we are not in authorize or something under authorize route, then redirect to authorize
            if (!isActive(['authorize', 'authorize/*'], true)){
                return redirect()->route('authorize');
            }
        }
        return $next($request);
    }

    private function timeout()
    {
        return now() >= $this->authorize->created_at->addMinutes(10);
    }
}
