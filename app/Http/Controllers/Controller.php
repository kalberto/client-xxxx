<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: *
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 23/03/2018
 * Time: *
 */
namespace App\Http\Controllers;

use App\Helpers\OSSAPI;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $auth;
	protected $ossapi;
	public function authenticate(){
		if(!isset($this->auth) || !isset($this->auth->result)){
			$login = env('API_HTT_LOGIN');
			$pass = env('API_HTT_PASS');
			if(!isset($login) || !isset($pass))
				return false;
			$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
			$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
			if($this->ossapi->hasError)
				return false;
			else
				return true;
		}else{
			return true;
		}
	}
}
