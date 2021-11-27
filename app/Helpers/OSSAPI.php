<?php
/**
 * Created by Kevin Testa.
 * Date: 23/10/2017
 * Time: 16:59
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 09/02/2018
 * Time: 17:00
 */

namespace App\Helpers;


use Exception;
use Illuminate\Support\Facades\Response;

class OSSAPI {
	private $url = false, $login, $password, $c;
	public $error, $hasError = false, $response, $status;
	function __construct($url, $proxy, $login, $password) {
		$this->url      = $url;
		$this->login    = $login;
		$this->password = $password;
		$this->c = curl_init();
		curl_setopt($this->c, CURLOPT_URL, $url);
		curl_setopt($this->c, CURLOPT_POST, 1);
		curl_setopt($this->c, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($this->c, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->c, CURLOPT_RETURNTRANSFER, 1);
	}
	function callAPI($method, $params, $auth) {
		$request = Array(
			'id'      => '1',
			'jsonrpc' => '2.0',
			'auth' => $auth,
			'method'  => $method,
			'params'  => (object)$params
		);
		if(env('APP_ENV') !== 'prod' && env('APP_ENV') != 'production')
			$request['params']->env = 'dev';
		$this->hasError = false;
		$this->error    = "";
		curl_setopt($this->c, CURLOPT_POSTFIELDS, json_encode($request) );
		try {
			if(!$return = curl_exec($this->c)){
				return Response::json(['msg' => curl_error($this->c)], 503);
			}
			if(curl_getinfo($this->c, CURLINFO_HTTP_CODE) == 200){
				return $this->response = json_decode($return, 0);
			}else{
				$this->error = "Failed to call API";
				$this->hasError = true;
				return 'error';
			}
		} catch(Exception $e) {
			$this->error = "Failed to call API: " . $e->getMessage();
			$this->hasError = true;
			return 'error';
		}
	}
	function getLastHTTPCode(){
		$code = curl_getinfo($this->c,CURLINFO_HTTP_CODE);
		return $code;
	}
	function __destruct() {
		curl_close($this->c);
	}
}