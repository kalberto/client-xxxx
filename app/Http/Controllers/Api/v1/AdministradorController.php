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
use App\Http\Request\Administrador\CreateRequest;
use App\Http\Request\Administrador\PassRequest;
use App\Http\Request\Administrador\UpdateRequest;
use App\LogAdministrador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;

class AdministradorController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('role:2')->except(['currentUser','menus']);
	}

	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return Response
	 */
    public function index(Request $request )
    {
	    //state - ativo ou não
    	//fields - quais fields é para trazer
	    //sort - order by
	    //limit - quantos registros
	    //q - busca na tabela
	    $params = $request->all();
	    try{
	    	$order_by = 'id';
	    	$asc = true;
	    	$fields = '';
	    	$state = true;
	    	$q = null;
	    	$limit = 13;
	    	$sort = null;
		    if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
				$limit = $params['limit'];
		    }
		    if(isset($params['sort'])){
		    	$order_by = $params['sort'];
		    	if(substr($order_by,0,1) == '-'){
		    		$asc = false;
		    		$order_by = substr($order_by,1);
			    }
		    }
		    if(isset($params['fields'])){
			    $fields = $params['fields'];
		    }
		    $data = Administrador::with('media.media_root')->whereNotNull('id');

		    if(isset($params['state']) ){
			    $state = boolval($params['state']);
			    $data = $data->where(['ativo' => $state]);
		    }
		    if(isset($params['q'])){
			    $q = $params['q'];
			    $data = $data->where('nome','like','%'.$q.'%')
				        ->orWhere('sobrenome','like','%'.$q.'%')
				        ->orWhere('email','like','%'.$q.'%');
		    }
		    if (Schema::hasColumn('administrador', $order_by)){
			    if($asc)
				    $data = $data->orderBy($order_by)->paginate($limit);
			    else
				    $data = $data->orderByDesc($order_by)->paginate($limit);
		    }
		    if($fields != ''){
		    	$fields = explode(',',$fields);
		    	$data->transform(function ($item,$key) use ($fields) {
		    		return collect($item)->only($fields);
			    });
		    }

		    if($data->total() > 0){
			    $statusCode = 200;
			    $response = $data->appends($params);
		    }
		    else{
			    $statusCode = 200;
			    $response = [
				    'msg' => 'Nenhum administrador encontrado!'
			    ];
		    }
	    }catch (Exception $e){
	    	$statusCode = 503;
	    	$response = [
	    		"error" => "Service Unavailable"
		    ];
	    }
	    Return Response::json($response, $statusCode);
    }

    /**
     * Show the form for creating a new resource.
     * RF0003
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //TODO RF0003
    }

    /**
     * Store a newly created resource in storage.
     * RF0003
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$data = $request->all();
        $data['api_token'] = str_random(46).date("YmdHis");
        $createRequest = new CreateRequest();
        $validate = $createRequest->validar($data);
        if(!$validate->fails()){
            if(Administrador::store($data, $request->ip())){
                $statusCode = 201;
                $response = [
                    'msg' => 'Administrador cadastrado com sucesso!'
                ];
            }else{
                $statusCode = 500;
                $response = [
                    'msg' => 'Ocorreu um erro ao salvar o Administrador'
                ];
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao salvar o Administrador',
                'error_validate' => $validate->errors()->all()
            ];
        }

	    return Response::json($response, $statusCode);
    }

    /**
     * Display the specified resource.
     * RF00
     * @param  \App\Administrador-id $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar esse administrador'
        ];
        if(isset($id) && is_numeric($id)){
            $administrador = Administrador::getUserWithMenu($id);
            if(isset($administrador)){
                $statusCode = 200;
                $response = $administrador;
            }
        }
	    return Response::json($response,$statusCode);
    }

    /**
     * Show the form for editing the specified resource.
     * RF0004
     * @param  \App\Administrador-id $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        //TODO RF0004
    }

    /**
     * Update the specified resource in storage.
     * RF0004
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Administrador-id $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        if(isset($id) && is_numeric($id) && Administrador::find($id) != null){
            $updateRequest = new UpdateRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                if(Administrador::edit($id,$data, $request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Administrador editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o Administrador'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o Administrador',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse Administrador.'
            ];
        }
	    return Response::json($response, $statusCode);
    }

	/**
	 * Update the specified resource in storage.
	 * RF0004
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Administrador-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function updatePass(Request $request, $id = null){
        if(isset($id) && is_numeric($id) && Administrador::find($id) != null){
            $updateRequest = new PassRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                if(Administrador::editPass($id,$data, $request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Administrador editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o Administrador'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o Administrador',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse Administrador.'
            ];
        }
		return Response::json($response, $statusCode);
	}

    /**
     * Remove the specified resource from storage.
     * RF0006
     * @param  \App\Administrador-id $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
	    if(isset($id) && is_numeric($id) && ($administrador = Administrador::find($id)) != null){
		    $data = array(
			    'administrador_id' => Auth::user()->id,
			    'registro_id' => $administrador->id,
			    'tabela' => 'administrador',
			    'tipo' => 'delete',
			    'ip' => $request->ip()
		    );
		    LogAdministrador::store($data);
	    	if($administrador->delete()){
			    $statusCode = 200;
			    $response = [
				    'msg' => 'Administrador deletado com sucesso!'
			    ];
		    }else{
			    $statusCode = 500;
			    $response = [
				    'msg' => 'Erro ao deletar o Administrador!'
			    ];
		    }
	    }
	    else{
		    $statusCode = 404;
		    $response = [
			    'msg' => 'Não foi possível encontrar esse Administrador.'
		    ];
	    }
	    return Response::json($response, $statusCode);
    }


    /**
     * Search for a list of resources from storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function search(Request $request){
        $limit = $request->has('limit') ? $request->get('limit') : 20;
        if($request->has('search'))
            $administradores = Administrador::search($request->get('search'))->paginate($limit);
        else
            $administradores = Administrador::paginate($limit);

        $statusCode = 200;
        $response = $administradores;
	    return Response::json($response, $statusCode);
    }

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function currentUser(){
		$adm = Auth::user();
		$adm->load('media.media_root');
		$permissions = array();
		foreach ($adm->modulos as $modulo){
			$permissions[$modulo->id] = true;
		}
		$adm['permissions'] = $permissions;
		return response($adm);
    }

	/**
	 * @param Request $request
	 * @param $id int
	 * @return \Illuminate\Http\Response
	 */
	public function menus(Request $request , $id){
		$statusCode = 200;
		$response = [];
        $adm = Administrador::find($id);
        if(isset($adm)){
            $response = $adm->menus();
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Menu não encontrado!'
            ];
        }
		return Response::json($response, $statusCode);
	}
}
