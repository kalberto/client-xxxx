<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 29/11/2017
 * Time: 17:18
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 17/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Cliente\Auth;

use App\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UsuarioAuthController extends Controller {

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/cliente';

	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->middleware('api_token:cliente_api')->except(['logout','loginGerenciador']);
	}

	/**
	 * Get the login username to be used by the controller.
	 *
	 * @return string
	 */
	public function username()
	{
		return 'login';
	}

	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		if(Usuario::login($user->id, $request)){
			return response()->json(
				['auth' => 'successful']
			,200);
		}else{
			return response()->json(['error' => 'Service Unavailable.'], 503);
		}
	}

	/**
	 * Get the failed login response instance.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendFailedLoginResponse(Request $request)
	{
		$errors = ['msg' => trans('auth.failed')];

		if ($request->expectsJson()) {
			return response()->json($errors, 422);
		}

		return redirect()->back()
		                 ->withInput($request->only($this->username(), 'remember'))
		                 ->withErrors($errors);
	}

	public function guard() {
		return Auth::guard('cliente_api');
	}

	public function showLoginForm() {
		if($this->guard()->check()){
			return redirect('/cliente');
		}
		return view( 'site/clientes/auth/login' );
	}

	public function isUserAuthenticated(Request $request) {
		return response()->json($this->guard()->check());
	}

	/**
	 * Log the user via api_token
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string  $api_token
	 * @return mixed
	 */
	public function loginGerenciador($api_token){
		$usuario = Usuario::getByApiToken($api_token);
		if($usuario){
			Auth::guard('cliente_api')->loginUsingId($usuario->getAuthIdentifier());
			$response = 'ok';
			$statusCode = 200;
		}else{
			$response = 'not_ok';
			$statusCode = 401;
		}
		Return Response::json($response, $statusCode);
	}

	/**
	 * Get the broker to be used during password reset.
	 *
	 * @return PasswordBroker
	 */
	protected function broker()
	{
		return Password::broker('usuario');
	}
}