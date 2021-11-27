<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 14/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/12/2017
 * Time: 15:55
 */

namespace App\Http\Controllers\Cliente;

use App\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends AreaClienteController
{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->middleware('role:2')->except(['currentUser','menus']);
	}



	/**
	 * @return \Illuminate\Http\Response
	 */
	public function currentUser(){
		return response(Auth::guard('cliente')->user());
	}

	/**
	 * @param Request $request
	 * @param $id int
	 * @return \Illuminate\Http\Response
	 */
	public function menus(Request $request , $id){
		$statusCode = 200;
		$response = [];
        $user = Usuario::find($id);
        if(isset($user)){
            $response = $user->menus();
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Menu não encontrado!'
            ];
        }
		return Response::json($response, $statusCode);
	}
}
