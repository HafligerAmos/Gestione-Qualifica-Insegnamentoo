<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->guard('allievi')->check() || auth()->guard('docenti')->check() || auth()->guard('admin')->check() || auth()->guard('segretarie')->check())
            return $next($request);
        else
            return redirect()->route('home');
    }
}
