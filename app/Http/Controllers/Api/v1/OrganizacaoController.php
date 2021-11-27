<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 22/03/2017
 * Time:09:35
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Api\v1;

use App\Documento;
use App\Helpers\OSSAPI;
use App\LogAdministrador;
use App\Organizacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Request\Organizacao\CreateRequest;
use App\Http\Request\Organizacao\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class OrganizacaoController extends Controller
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
		//sort - order by
		//limit - quantos registros
		//q - busca na tabela
		$params = $request->all();
        $q = null;
        $limit = 13;
        $sort = null;
        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $limit = $params['limit'];
        }
        $data = Organizacao::whereNotNull('id');
        if(isset($params['q'])){
            $q = $params['q'];
            $data = $data->where('nome','like','%'.$q.'%');
        }
        $data = $data->orderBy('nome')->paginate($limit);
        if($data->total() > 0){
            $statusCode = 200;
            $response = $data->appends($params);
        }
        else{
            $statusCode = 200;
            $response = [
                'msg' => 'Nenhuma organização encontrada!'
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
        $createRequest = new CreateRequest();
        $validate = $createRequest->validar($data);
        if(!$validate->fails()){
            if(Organizacao::findBySlug(str_slug($data['nome']))){
                $statusCode = 422;
                $response = [
                    'error_validate' => [
                        'nome' => [
                            0 => 'Já existe uma categoria com esse nome'
                        ]
                    ]
                ];
            }else{
                if(Organizacao::store($data, $request->ip())){
                    $statusCode = 201;
                    $response = [
                        'msg' => 'Organização cadastrada com sucesso!',
                        'slug' => str_slug($data['nome'])
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao salvar a organização'
                    ];
                }
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao salvar a organização',
                'error_validate' => $validate->errors()
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 * RF00
	 * @param  \App\Organizacao-slug $slug
	 * @return \Illuminate\Http\Response
	 */
	public function show($slug = null)
	{
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar essa organização'
        ];
        if(isset($slug)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $organizacao = Organizacao::getOrganizacaoWithEmpresas($slug);
            $paramsAPI = (object)array(
                'fields' => ['NOME', 'DOCUMENTO'],
                'filter' => [
                    'DOCUMENTO' => $organizacao->documentos,
                ],
                'limit' => 1,
                'page' => 1,
            );
            $clientes = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );
            if(isset($clientes->result) && isset($clientes->result->info)){
                $paramsAPI = (object)array(
                    'fields' => ['NOME', 'DOCUMENTO'],
                    'filter' => [
                        'DOCUMENTO' => $organizacao->documentos,
                    ],
                    'limit' => $clientes->result->info->number_of_rows,
                    'page' => 1,
                );
                $clientes = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );
                if(isset($clientes->result) && isset($clientes->result->items)){
                    $statusCode = 200;
                    $organizacao->empresas = $clientes->result->items;
                }
            }
            if(isset($organizacao)){
                $statusCode = 200;
                $response = $organizacao;
            }
        }
		return Response::json($response,$statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 * RF0004
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Organizacao-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id = null)
	{
        if(isset($id) && is_numeric($id) && Organizacao::find($id) != null){
            $updateRequest = new UpdateRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                $org = Organizacao::findBySlug(str_slug($data['nome']));
                if( isset($org) && $org->id != $id){
                    $statusCode = 422;
                    $response = [
                        'error_validate' => [
                            'nome' => [
                                0 => 'Já existe uma categoria com esse nome'
                            ]
                        ]
                    ];
                }else{
                    if(Organizacao::edit($id,$data,$request->ip())){
                        $statusCode = 200;
                        $response = [
                            'msg' => 'Organização editada com sucesso!'
                        ];
                    }else{
                        $statusCode = 500;
                        $response = [
                            'msg' => 'Ocorreu um erro ao editar a organização'
                        ];
                    }
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar a organização',
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
	 * Remove the specified resource from storage.
	 * RF0006
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Organizacao-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$slug = null){
		if(isset($slug) && ($organizacao = Organizacao::findBySlug($slug)) != null){
			foreach ($organizacao->documentos as $documento){
				$documento->delete();
			}
			foreach ($organizacao->usuarios as $usuario){

				$usuario->delete();
			}
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $organizacao->id,
				'tabela' => 'organizacao',
				'tipo' => 'delete',
				'ip' => $request->ip()
			);
			LogAdministrador::store($data);
			if($organizacao->delete()){
				$statusCode = 200;
				$response = [
					'msg' => 'Organização deletada com sucesso!'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar a organização!'
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar essa organização.'
			];
		}

		return Response::json($response, $statusCode);
	}

	public function addDocumento($slug,$documento){
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar essa organização'
		];
		if(isset($slug)){
			if(isset($documento)){
				$organizacao = Organizacao::findBySlug($slug);
				if(isset($organizacao)){
					if($empresa = $organizacao->documentos()->where('documento','=',$documento)->limit(1)->first()){
						$statusCode = 200;
						$response = [
							'msg'=>'O Documento já existe nessa empresa.'];
					}else{
						$empresa = new Documento();
						$empresa->documento = $documento;
						$empresa->organizacao()->associate($organizacao);
						$empresa->save();
						if($organizacao->save()){
							$statusCode = 200;
							$response = [
								'msg'=>'Documento adicionado com sucesso.'];
						}else{
							$statusCode = 503;
							$response = ['msg'=>'Service Unavailable'];
						}
					}
				}
			}else{
				$statusCode = 422;
				$response = [
					'msg' => 'É necessário um Documento'
				];
			}
		}
		return Response::json($response,$statusCode);
	}

	public function removeDocumento($slug,$documento){
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar essa organização'
		];
		if(isset($slug)){
			if(isset($documento)){
				$organizacao = Organizacao::findBySlug($slug);
				if(isset($organizacao)){
					if($empresa = $organizacao->documentos()->where('documento','=',$documento)->limit(1)->first()){
						$empresa->delete();
						if($organizacao->save()){
							$statusCode = 200;
							$response = [
								'msg'=>'Documento removido com sucesso.'];
						}else{
							$statusCode = 503;
							$response = ['msg'=>'Service Unavailable'];
						}
					}else{
						$statusCode = 200;
						$response = [
							'msg' => 'O Documento não existe nessa empresa.'];
					}
				}
			}else{
				$statusCode = 422;
				$response = [
					'msg' => 'É necessário um Documento'
				];
			}
		}
		return Response::json($response,$statusCode);
	}

	public function documento($slug){
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar essa organização'
		];
		if(isset($slug)){
			if(!$this->authenticate())
				throw new Exception('API ERROR');
			$organizacao = Organizacao::getOrganizacaoWithEmpresas($slug);
			$paramsAPI = (object)array(
				'fields' => ['NOME', 'DOCUMENTO'],
				'filter' => [
					'DOCUMENTO' => $organizacao->documentos,
				],
				'limit' => 1,
				'page' => 1,
			);
			$statusCode = 503;
			$response = [
				'msg' => 'A API não está disponivel.'
			];
			$clientes = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );
			if(isset($clientes->result) && isset($clientes->result->info)){
				$paramsAPI = (object)array(
					'fields' => ['NOME', 'DOCUMENTO'],
					'filter' => [
						'DOCUMENTO' => $organizacao->documentos,
					],
					'limit' => $clientes->result->info->number_of_rows,
					'page' => 1,
				);
				$clientes = $this->ossapi->callAPI( 'services.client_list', $paramsAPI , $this->auth->result->auth );
				if(isset($clientes->result) && isset($clientes->result->items)){
					$statusCode = 200;
					$response = $clientes->result->items;
				}
			}
		}
		return Response::json($response,$statusCode);
	}

	/**
	 * @param $slug string
	 */
	public function getUsuarios($slug){
		$usuarios = Organizacao::getUsuarios($slug);
		$statusCode = 200;
		$response = $usuarios;
		return Response::json($response, $statusCode);
	}
}
