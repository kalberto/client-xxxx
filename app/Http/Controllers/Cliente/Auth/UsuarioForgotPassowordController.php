<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/12/2017
 * Time: 08:58
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 05/12/2017
 * Time: 08:58
 */


namespace App\Http\Controllers\Cliente\Auth;

use App\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;

class UsuarioForgotPassowordController extends Controller{
	use SendsPasswordResetEmails;

	public function __construct() {
		$this->middleware('guest');
	}

	public function guard() {
		return Auth::guard('cliente_api');
	}

	/**
	 * Get the response for a successful password reset link.
	 *
	 * @param  string  $response
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendResetLinkResponse($response)
	{
		return back()->with('msg', trans($response));
	}

	/**
	 * Get the response for a failed password reset link.
	 *
	 * @param  \Illuminate\Http\Request
	 * @param  string  $response
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendResetLinkFailedResponse(Request $request, $response)
	{
		return back()->withErrors(
			['msg' => trans($response)]
		);
	}

	public function showLinkRequestForm()
	{
		return view('site.clientes.auth.password-request');
	}

	public function sendResetLinkEmail(Request $request){
		if($request->login){
			$data['reset_token'] = str_random(234).date("YmdHis");
			$usuario = Usuario::getByLogin($request->login);
			if(isset($usuario)){
				$usuario->fill($data);
				$usuario->save();
				$usuario->sendResetPasswordNotification($usuario->reset_token, $usuario->nome);
				$response = [
					'msg' => 'Email enviado'
				];
				$statusCode = 200;
			}else{
				$response = [
					'msg' => 'Login invalido'
				];
				$statusCode = 401;
			}

		}else{
			$response = [
				'msg' => 'Login obrigatório'
			];
			$statusCode = 401;
		}
		return Response::json($response, $statusCode);
	}
}