<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 27/12/2017
 * Time: 16:43
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 27/12/2017
 * Time: 16:43
 */

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class HasCredentials {

	public static function checkRole($role){
		$modulos = Auth::user()->modulos()->where('id','=',$role);
		if($modulos->count() >= 1) {
			return true;
		}
		else
			return false;
	}

}