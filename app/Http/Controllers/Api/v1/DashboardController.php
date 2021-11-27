<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/10/2017
 * Time: 18:20
 */

namespace App\Http\Controllers\Api\v1;

use App\Administrador;
use App\Faq;
use App\Lead;
use App\Manual;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:1' );
	}
	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request )
	{
		$statusCode = 200;
		$response = [];
		$response['total_administradores'] = Administrador::countTotal();
		$response['total_usuarios'] = Usuario::countTotal();
		$response['total_faqs'] = Faq::countTotal();
		$response['total_manuais'] = Manual::countTotal();
		$response['total_leads'] = Lead::countTotal();

		Return Response::json($response, $statusCode);
	}
}
