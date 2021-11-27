<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 31/10/2017
 * Time: 09:21
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/01/2018
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Request\Usuario\PassRequest;
use App\LogAdministrador;
use App\Organizacao;
use App\Usuario;
use App\Http\Request\Usuario\CreateRequest;
use App\Http\Request\Usuario\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;

class UsuarioController extends Controller{

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('role:3');
	}

	public function index(Request $request){
		//state - ativo ou não
		//fields - quais fields é para trazer
		//sort - order by
		//limit - quantos registros
		//q - busca na tabela
		$params = $request->all();
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
        $data = Usuario::with('organizacao')->whereNotNull('id');

        if(isset($params['state'])){
            $state = boolval($params['state']);
            $data = $data->where(['ativo' => $state]);
        }
        if(isset($params['q'])){
            $q = $params['q'];
            $data = $data->where('nome','like','%'.$q.'%')
                ->orWhere('sobrenome','like','%'.$q.'%')
                ->orWhere('email','like','%'.$q.'%')
                ->orWhere('login','like','%'.$q.'%');
        }
        if (Schema::hasColumn('usuario', $order_by)){
            if($asc)
                $data = $data->orderBy($order_by)->paginate($limit);
            else
                $data = $data->orderByDesc($order_by)->paginate($limit);
            $data->setCollection($data->getCollection()->makeVisible('api_token'));
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
                'msg' => 'Nenhum usuário encontrado!'
            ];
        }
		Return Response::json($response, $statusCode);
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
            if($organizacao = Organizacao::findBySlug($data['slug'])){
                $data['organizacao_id'] = $organizacao->id;
                if(Usuario::store($data, $request->ip())){
                    $statusCode = 201;
                    $response = [
                        'msg' => 'Usuário cadastrado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao salvar o Usuário'
                    ];
                }
            }
            else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Não foi possivel encontrar essa organização'
                ];
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao salvar o Usuário',
                'error_validate' => $validate->errors()->all()
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 * RF00
	 * @param  \App\Usuario-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id = null)
	{
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar esse usuário'
        ];
        if(isset($id) && is_numeric($id)){
            $usuario = Usuario::getUserWithMenu($id);
            if(isset($usuario)){
                $statusCode = 200;
                $response = $usuario;
            }
        }
		return Response::json($response,$statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 * RF0004
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Usuario-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id = null)
	{
        if(isset($id) && is_numeric($id) && Usuario::find($id) != null){
            $updateRequest = new UpdateRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                if(Usuario::edit($id,$data,$request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Usuário editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o usuário'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o usuário',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse usuário.'
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
        if(isset($id) && is_numeric($id) && Usuario::find($id) != null){
            $updateRequest = new PassRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                if(Usuario::editPassAdm($id,$data, $request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Usuário editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o Usuário'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o Usuário',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse Usuário.'
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Remove the specified resource from storage.
	 * RF0006
	 * @param  \App\Usuario-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id = null)
	{
		if(isset($id) && is_numeric($id) && ($usuario = Usuario::find($id)) != null){
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $id,
				'tabela' => 'usuario',
				'tipo' => 'delete',
				'ip' => $request->ip()
			);
			LogAdministrador::store($data);
			if($usuario->delete()){
				$statusCode = 200;
				$response = [
					'msg' => 'Usuário deletado com sucesso!'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o usuário!'
				];
			}
		}
		else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse usuário.'
			];
		}

		return Response::json($response, $statusCode);
	}
}