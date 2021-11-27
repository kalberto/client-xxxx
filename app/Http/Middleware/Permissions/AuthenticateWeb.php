<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 14/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 14/11/2017
 * Time: *
 */

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateWeb {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next,$role)
	{
		$modulos = Auth::user()->modulos()->where('id','=',$role);
		if($modulos->count() >= 1)
			return $next($request);
		else
			return response()->json(['error' => 'Unauthorized.'], 401);
	}
}