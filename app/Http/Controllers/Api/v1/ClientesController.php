<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Helpers\OSSAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;
use function Sodium\library_version_minor;

class ClientesController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('role:3');
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
	}

	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0)
            $limit = $params['limit'];
        else
            $limit = 20;
        if(isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)
            $page = $params['page'];
        else
            $page = 1;
        $paramsAPI = Array(
            'fields' => ['ID','INSCRICAO_MUNICIPAL','NOME', 'DOCUMENTO','RG_INSCRICAO','FANTASIA'],//, 'EMAIL'
            //'sort' => ['-asc' => 'NOME'],
            //'filter' => ['nome' => 'X1 TECNOLOGIAS LTDA - EPP'],
            'limit' => $limit,
            'page' => $page,

        );
        $new = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );

        $statusCode = 200;
        $response = [
            'total' => $new->result->info->number_of_rows,
            'current_page' => $new->result->info->current_page,
            'data' => $new->result->items
        ];

        return Response::json($response, $statusCode);
	}

	public function search(Request $request){
		$params = $request->all();
		if(!$this->authenticate())
			throw new Exception('API ERROR');
		if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0)
			$limit = $params['limit'];
		else
			$limit = 20;
		if(isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)
			$page = $params['page'];
		else
			$page = 1;
		if(isset($params['search'])){
			$encoding = 'UTF-8';
			$search = mb_convert_case($params['search'], MB_CASE_UPPER, $encoding);
			$doc = preg_replace(["/\//","/\./","/\-/"],'',$search);
			$paramsAPI = Array(
				'fields' => ['ID','INSCRICAO_MUNICIPAL','NOME','DOCUMENTO','RG_INSCRICAO','FANTASIA'],//, 'EMAIL'
				//'sort' => ['-asc' => 'NOME'],
				'filter' => [
					'-or'=>[
						'nome' => ['-like' => ['%'.$search.'%']],
						'ID' =>['-like'=> ['%'.$search.'%']],
						'INSCRICAO_MUNICIPAL' => ['-like' => ['%'.$search.'%']],
						'NOME' =>['-like'=> ['%'.$search.'%']],
						'DOCUMENTO' => ['-like'=> [$doc != '' ? '%'.$doc.'%' : '%'.$search.'%']],
						'RG_INSCRICAO' => ['-like'=> ['%'.$search.'%']],
						'FANTASIA' => ['-like' => ['%'.$search.'%']]
					]
				],
				'limit' => $limit,
				'page' => $page,
			);
		}else{
			$paramsAPI = Array(
				'fields' => ['ID','INSCRICAO_MUNICIPAL','NOME', 'DOCUMENTO','RG_INSCRICAO','FANTASIA'],//, 'EMAIL'
				'limit' => $limit,
				'page' => $page,
			);
		}

		if(isset($params['key']))
			$paramsAPI['filter'] = ['nome' => $params['key']];
		$new = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );
		$statusCode = 200;
		$response = [
			'total' => $new->result->info->number_of_rows,
			'current_page' => $new->result->info->current_page,
			'data' => $new->result->items
		];
		return Response::json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 * RF00
	 * @param  \App\Administrador-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($documento = null)
	{
		if(!$this->authenticate())
			throw new Exception('API ERROR');
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar esse cliente'
		];
		$paramsAPI = Array(
			'fields' => ['ID','INSCRICAO_MUNICIPAL','NOME', 'DOCUMENTO','RG_INSCRICAO','FANTASIA', 'EMAIL'],
			'documento' => $documento
		);
		$new = $this->ossapi->callAPI( 'services.get_client', $paramsAPI , $this->auth->result->auth );
		if(isset($new->result->items[0])){
			$response = $new->result->items[0];
			$statusCode = 200;
		}
		return Response::json($response,$statusCode);
	}
}
