<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeDeveloperInLocalAndDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $appEnv = env('APP_ENV', 'local');
        $appDebug = env('APP_DEBUG', true);
        if (($appEnv !== 'production' || $appDebug) && (!auth()->check() || !auth()->user()->isDev())) {
            dd($appEnv !== 'production', $appDebug, !auth()->check());
            return redirect()->to('https://codethereal.com');
        }
        return $next($request);
    }
}
