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
     * @var \App\Authorize
     */
    private Authorize $authorize;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Authorize::active() && auth()->check()) {
            $this->authorize = Authorize::make();

            if ($this->authorize->attempt < 1) {
                Mail::to($request->user())
                    ->send(new AuthorizeDeviceMail($this->authorize));
                $this->authorize->increment('attempt');
            }

            if ($this->timeout()) {
                auth()->guard()->logout();
                $request->session()->invalidate();

                return redirect()->route('login')->withErrors(['status' => 'You are logged out of system, please follow the link we sent before 10 minutes to authorize your device, the link will be valid with same IP for 1 hour.']);
            }

            return redirect()->route('authorize');
        }
        return $next($request);
    }

    private function timeout()
    {
        return now() >= $this->authorize->created_at->addMinutes(10);
    }
}
