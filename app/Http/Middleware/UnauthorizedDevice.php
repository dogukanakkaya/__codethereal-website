<?php

namespace App\Http\Middleware;

use App\Models\Authorize;
use Closure;
use Illuminate\Http\Request;

class UnauthorizedDevice
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Authorize::active()) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
