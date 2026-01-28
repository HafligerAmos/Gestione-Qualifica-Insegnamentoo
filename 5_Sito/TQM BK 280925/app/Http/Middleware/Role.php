<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $first_role, $second_role = null, $third_role = null)
    {
		// If has the permission to go on
		if((auth()->guard($first_role)->check()) ||
		   (!is_null($second_role) && auth()->guard($second_role)->check()) ||
		   (!is_null($third_role) && auth()->guard($third_role)->check())){
			// Continue with the request
        	return $next($request);
		// If hasn't the permission to go on
		} else {
			// Return back
			return redirect()->back();
		}
    }
}
