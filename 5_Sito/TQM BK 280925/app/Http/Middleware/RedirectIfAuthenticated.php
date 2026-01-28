<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'allievi':
                if (auth()->guard($guard)->check()) {
                    return redirect()->route('valutazione.mie');
                }
                break;
            case 'docenti':
                if (auth()->guard($guard)->check()) {
                    return redirect()->route('valutazione.mie');
                }
                break;
            case 'admin':
                if (auth()->guard($guard)->check()) {
                    return redirect()->route('pannello.home');
                }
                break;
			case 'segretarie':
                if (auth()->guard($guard)->check()) {
                    return redirect()->route('pannello.home');
                }
                break;
            default:
                if (auth()->guard($guard)->check()) {
                    return redirect('/');
                }
                break;
        }
        return $next($request);
    }
}
