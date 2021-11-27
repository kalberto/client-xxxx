<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/07/2019
 * Time: 15:24
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/07/2019
 * Time: 15:24
 */

namespace App\Http\Traits;

trait NumbersOnly {

	/**
	 * Return only the number from the given string
	 *
	 * @param  string $value
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function getOnlyNumbers($value){
		return preg_replace("/[^0-9]/",'',$value);
	}

	protected static function static_getOnlyNumbers($value){
		return preg_replace("/[^0-9]/",'',$value);
	}
}