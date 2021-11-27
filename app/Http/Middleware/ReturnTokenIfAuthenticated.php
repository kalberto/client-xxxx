<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ReturnTokenIfAuthenticated
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
	    if (Auth::guard($guard)->check() && $request->wantsJson()){
			    $content = [
				    'auth' => Auth::guard($guard)->user()->api_token,
				    'api_token' => 'Authentication Successful2'
			    ];
			    return response($content);
		}
        return $next($request);
    }
}
