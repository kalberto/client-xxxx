<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/11/2017
 * Time: 17:33
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 06/11/2017
 * Time: 17:33
 */

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole {
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
		if($modulos->count() >= 1) {
			return $next( $request );
		}
		else
			return response()->json(['error' => 'Unauthorized.'], 401);
	}
}