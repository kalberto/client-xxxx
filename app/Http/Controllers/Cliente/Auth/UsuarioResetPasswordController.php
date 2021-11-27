<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/12/2017
 * Time: 09:07
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 05/12/2017
 * Time: 09:07
 */

namespace App\Http\Controllers\Cliente\Auth;

use App\Resets;
use App\Usuario;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;

class UsuarioResetPasswordController extends Controller{
	use ResetsPasswords;
	protected $redirectTo = '/cliente';

	public function __construct() {
		$this->middleware('guest');
	}

	public function guard() {
		return Auth::guard('cliente_api');
	}

	public function showResetForm(Request $request, $token = null)
	{
		return view('site.clientes.auth.password-reset')->with(
			['token' => $token]
		);
	}

	public function reset(Request $request){
		$this->validate($request, $this->rules(), $this->validationErrorMessages());
		$usuario = Usuario::getByResetToken($request->token);
		if($usuario){
			$usuario->reset_token = null;
			$usuario->password = $request->password;
			$usuario->save();
			$response = [
				'msg' => 'Senha alterada',
				'url' => url('/cliente')
			];
			$statusCode = 200;
		}else{
			$response = [
				'msg' => 'Token inválido'
			];
			$statusCode = 401;
		}
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the password reset validation rules.
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'token' => 'required',
			'password' => 'required|confirmed|min:6',
		];
	}
}