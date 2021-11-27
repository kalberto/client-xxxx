<?php

namespace App\Http\Middleware;

use Closure;

class EnsureWantsJson
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
        $request->headers->add([
            'Accept' => 'application/json'
        ]);

        return $next($request);
    }
}
