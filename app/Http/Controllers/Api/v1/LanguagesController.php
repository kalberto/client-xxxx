<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/10/2020
 * Time: 09:54
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LanguagesController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct() {
	}

	public function getLanguages(){
		$registros = DB::table('languages')->get()->toArray();
		$statusCode = 200;
		$response = [
			'registros' => $registros
		];
		return response()->json($response,$statusCode);
	}
}
