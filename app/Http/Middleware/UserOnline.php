<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOnline
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()) {
            $expiresAt = now()->addMinutes(1);
            cache()->put('user-is-online-' . Auth::id(), true, $expiresAt);
        }
        return $next($request);
    }
}
