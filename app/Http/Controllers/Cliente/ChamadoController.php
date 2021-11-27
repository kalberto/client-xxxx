<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 28/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\LogUsuario;
use DeepCopy\f007\FooDateTimeZone;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;

class ChamadoController extends AreaClienteController
{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct(){
		parent::__construct();
		$this->middleware(array('role:3','cliente_api'));
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/chamados';
	}

	public function index(Request $request){
		$this->beforeReturn();
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualização dos chamados',
			'area' => 'Chamados'
		);
		LogUsuario::store($dado);
		return view('site.clientes.chamados.chamados',$this->data);
	}

	/**
	 * Get the chamados by organizacao
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function getChamadosCliente(Request $request){
		if(!$this->authenticate())
			throw new \Exception('API ERROR');
		$documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
		$params = $request->all();
		$limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0 ) ? $params['limit'] : 10;
		$page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)? $params['page'] : 1;
		$tempData = [];
		$tempOrd = [];
		foreach ($documentos as $documento){
			$paramsAPI = Array(
				'DOCUMENTO' => $documento,
				'status'=> ["Atualizado", "Não Resolvido", "Aguardando"],
			);
			$chamados = $this->ossapi->callAPI( 'services.incident_list', $paramsAPI , $this->auth->result->auth );
			if(isset($chamados->result) && isset($chamados->result->incident)){
				foreach ($chamados->result->incident as $chamado){
					$tempData[] = [
						'DOCUMENTO' => $documento,
						'nome' => isset($chamado->ProductData) ? $chamado->ProductData : 'Não relacionado',
						'protocolo' => $chamado->ReferenceNumber,
						'data_abertura' => date('d/m/Y',strtotime($chamado->CreatedTime)),
						'categoria' => $chamado->description,
						'status' => $chamado->state,
						'link' => url("/cliente/chamados/{$documento}/{$chamado->ReferenceNumber}")
					];
					$tempOrd[] = strtotime($chamado->CreatedTime);
				}
			}
		}
		arsort($tempOrd);
		$size_of_array = sizeof($tempOrd);
		$start = ($limit*($page - 1));
		$end = ($limit*$page);
		$next_page = $size_of_array > $end ? url("/cliente/chamados/resolved?page=".($page+1).'&limit='.$limit) : null;
		$end = $start > $size_of_array ? $size_of_array  : $end;
		$data = [
			'current_page' => $page,
			'data' => [],
			'from' => $page,
			'next_page_url' => $next_page,
			'per_page' => $limit,
			'total' => $size_of_array
		];
		if($start < $size_of_array){
			$paginated_array = array_slice(array_keys($tempOrd),$start,($end-$start));
			foreach ($paginated_array as $key => $item){
				$data['data'][] = $tempData[$item];
			}
		}
		return Response::json($data, 200);
	}

	/**
	 * Get the chamados by organizacao
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getLastChamados(Request $request){
		try{
			if(!$this->authenticate())
				throw new Exception('API ERROR');
			$documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
			$tempData = ['chamados' => []];
			$tempOrd = [];
			foreach ($documentos as $documento){
				$paramsAPI = Array(
					'DOCUMENTO' => $documento,
					'status'=> ["Atualizado", "Não Resolvido", "Aguardando", "Resolvido"],
					'sort'=> [['-desc'=> 'Category']]
				);
				$chamados = $this->ossapi->callAPI( 'services.incident_list', $paramsAPI , $this->auth->result->auth );
				if(isset($chamados->result) && isset($chamados->result->incident)){
					foreach ($chamados->result->incident as $chamado){
						$tempData['chamados'][] = [
							'DOCUMENTO' => $documento,
							'nome' => isset($chamado->ProductData) ? $chamado->ProductData : 'Não relacionado',
							'protocolo' => $chamado->ReferenceNumber,
							'data_abertura' => date('d/m/Y',strtotime($chamado->CreatedTime)),
							'categoria' => $chamado->description,
							'status' => $chamado->state,
							'link' => url("/cliente/chamados/{$documento}/{$chamado->ReferenceNumber}")
						];
						$tempOrd[] = strtotime($chamado->CreatedTime);
					}
					arsort($tempOrd);
				}
			}
			$data =['chamados' => []];
			foreach ($tempOrd as $key => $item){
				if(sizeof($data['chamados']) < 3)
					$data['chamados'][] = $tempData['chamados'][$key];
				else
					break;
			}
			$statusCode = 200;
			$response = $data;
		}catch (Exception $e){
			$statusCode = 503;
			$response = [
				"error" => "Service Unavailable"
			];
		}
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the chamados resolvidos by organizacao
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function getChamadosResolvidos(Request $request){
		if(!$this->authenticate())
			throw new \Exception('API ERROR');
		$documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
		$params = $request->all();
		$limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0 ) ? $params['limit'] : 10;
		$page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)? $params['page'] : 1;
		$tempData = [];
		$paramsAPI = Array(
			"sort" => [(object)["-desc"=> "createdtime"]],
			"filter" => [
				"DOCUMENTO" => $documentos,
				"state"=> "Resolvido",
			],
			"limit" => $limit,
			"page"=>$page,
			"fields"=>["description","state","tn","documento","createdtime","name","product"]
		);
		$chamados = $this->ossapi->callAPI( 'services.incident_history_list', $paramsAPI , $this->auth->result->auth );
		if(isset($chamados->result) && isset($chamados->result->items)){
			foreach ($chamados->result->items as $chamado){
				$tempData[] = [
					'DOCUMENTO' => $chamado->documento,
					'nome' => isset($chamado->product) ? $chamado->product : 'Não relacionado',
					'protocolo' => $chamado->tn,
					'data_abertura' => date('d/m/Y',strtotime($chamado->createdtime)),
					'categoria' => $chamado->description,
					'status' => $chamado->state,
					'link' => url("/cliente/chamados/{$chamado->documento}/{$chamado->tn}")
				];
			}
		}
		$size_of_array = isset($chamados->result->info->number_of_rows) ? $chamados->result->info->number_of_rows : 0;
		$end = ($limit*$page);
		$next_page = $size_of_array > $end ? url("/cliente/chamados/resolved?page=".($page+1).'&limit='.$limit) : null;
		$data = [
			'current_page' => isset($chamados->info->current_page) ? $chamados->info->current_page : 0,
			'data' => $tempData,
			'from' => $page,
			'next_page_url' => $next_page,
			'per_page' => $limit,
			'total' => $size_of_array
		];
		return Response::json($data, 200);
	}
	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function detalhe(Request $request ,$documento,$id){
		$this->beforeReturn();
		if(!$this->authenticate())
			throw new Exception('API ERROR');
		$paramsAPI = Array(
			'DOCUMENTO' => $documento,
			'tn' => $id
		);
		$data = [];
		$chamado = $this->ossapi->callAPI( 'services.incident_list', $paramsAPI , $this->auth->result->auth );
		if(isset($chamado->result) && isset($chamado->result->incident) && sizeof($chamado->result->incident) >= 1){
			$chamado = $chamado->result->incident[0];
			if($chamado->state == "Resolvido"){
				$date = new \DateTime($chamado->ClosedTime);
				$date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
				$data['ultima_atualizacao'] = $date->format('d/m/Y');
			}
			else{
				$date = new \DateTime($chamado->UpdatedTime);
				$date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
				$data['ultima_atualizacao'] = $date->format('d/m/Y');
			}
			$date = new \DateTime($chamado->CreatedTime);
			$date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
			$data['data_abertura'] = $date->format('d/m/Y');
			$data['num_protocolo'] = $chamado->ReferenceNumber;
			$data['status'] = $chamado->state;
			$data['tipo'] = $chamado->description;
			$data['assunto'] = $chamado->type;
			$data['solicitante'] = $chamado->contact_name;
			$data['contato'] = 'Não Implementado'; // TODO
			$data['id'] = $chamado->ID;
			$this->data['chamado'] = $data;
		}else{
			$this->data['chamado'] = false;
		}
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualizar detalhe do chamado - '.$id,
			'area' => 'Chamados'
		);
		LogUsuario::store($dado);
		return view('site/clientes/chamados/detalhe',$this->data);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Http\Response
	 */
	public function getIncidentNodes(Request $request,$id){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $paramsIndicentNodes = Array(
            'incident' => $id
        );
        $incidentNodes = $this->ossapi->callAPI( 'services.get_incident_notes', $paramsIndicentNodes , $this->auth->result->auth );
        if(isset($incidentNodes->result) && isset($incidentNodes->result->incident) && sizeof($incidentNodes->result->incident) >= 1){
            $incidentNodes = $incidentNodes->result->incident;
            $nodes = [];
            $i = 1;
            foreach ($incidentNodes as $incident_node){
                $node['usuario']['nome'] = $incident_node->origem;
                $node['usuario']['image'] = 'image';
                $node['text'] = $incident_node->Text;
                $date = new \DateTime($incident_node->CreatedTime);
                $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
                $node['date'] = $date->format('d/m/Y');
                $node['time'] = $date->format('H:i');
                $node['id'] = 'mensagem'.$i;
                $i++;
                $nodes[] = $node;
            }
            $data['incident_nodes'] = $nodes;
        }else{
            $data['incident_nodes'] = [];
        }
        $response = $data;
        $statusCode = 200;
		Return Response::json($response, $statusCode);
	}

	/** rota novo chamado **/
	public function abrirChamado(Request $request){
		$this->beforeReturn();
		$produtos = array();
		if(!$this->authenticate()){
			$produtos = [];
		}else{
			$documentos = Auth::guard( 'cliente_api' )->user()->organizacao()->first()->documentos()->pluck('documento');
			$paramsAPI = Array(
				'filter' => [ 'DOCUMENTO' => $documentos,"status" => "Habilitado" ],
				'page' => 1,
				'limit' => 1,
				'fields' => ["SVCID", "CONFIG_TECNICAS"]
			);
			$servicos  = $this->ossapi->callAPI( 'services.list', $paramsAPI, $this->auth->result->auth );
			if(isset($servicos->result) && isset($servicos->result->info) && $servicos->result->info->number_of_rows > 0){
				$paramsAPI['limit'] = $servicos->result->info->number_of_rows;
				$servicos  = $this->ossapi->callAPI( 'services.list', $paramsAPI, $this->auth->result->auth );
				if(isset($servicos->result) && isset($servicos->result->items)){
					$produtos_api = $this->ossapi->callAPI('services.get_tipoServico_for_product',array(),$this->auth->result->auth);
					if(isset($produtos_api->result)){
						$produtos_api = $produtos_api->result;
						foreach ($servicos->result->items as $key => $item){
							if(isset($item->config_tecnicas)){
								$field_name = $item->config_tecnicas->TipoServico;
								if(isset($produtos_api->$field_name)){
									foreach ($produtos_api->$field_name as $prod){
										if(!isset($produtos[$prod])){
											$produtos[$prod] = [
												'nome_do_produto' => $prod,
												'icone' => 'icone-'.str_slug($prod),
												'url' => str_slug($prod),
												'id' => 'id-'.str_slug($prod),
												'products' => [$key]
											];
										}else{
											$produtos[$prod]['products'][] = $key;
										}
									}
								}
							}
						}
					}else{
						$produtos = [];
					}
				}
			}else{
				$produtos = [];
			}
		}
		$this->data['produtos'] = $produtos;
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualização da tela de abertura de chamados ',
			'area' => 'Chamados'
		);
		LogUsuario::store($dado);
		return view('site/clientes/chamados/abrir-chamado',$this->data);
	}

	/**
	 * Get the service list by DOCUMENTO
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getServiceList(Request $request){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
        $paramsAPI = Array(
            'filter' => [ 'DOCUMENTO' => $documentos ,"status" => "Habilitado"],
            'page' => 1,
            'limit' => 0,
            'fields' => ["SVCID", "CONFIG_TECNICAS"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        if(isset($servicos->result) && isset($servicos->result->info) && $servicos->result->info->number_of_rows > 0){
            $data = [];
            $paramsAPI['limit'] = $servicos->result->info->number_of_rows;
            $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
            $params = $request->all();
            if(isset($servicos->result) && isset($servicos->result->items)){
                if(isset($params['url_produto'])){
                    $produtos_api = $this->ossapi->callAPI('services.get_tipoServico_for_product',array(),$this->auth->result->auth);
                    if(isset($produtos_api->result)){
                        $produtos = [];
                        $produtos_api = $produtos_api->result;
                        foreach ($produtos_api as $key => $item){
                            foreach ($item as $prod){
                                if(str_slug($prod) == $params['url_produto'] && !isset($produtos[$key])){
                                    $produtos[$key] = true;
                                }
                            }
                        }
                        foreach ($servicos->result->items as $key => $item){
                            if(isset($item->config_tecnicas)){
                                $nome_servico = $item->config_tecnicas->TipoServico;
                                foreach ($produtos as $key2 => $item2){
                                    if($nome_servico == $key2){
                                        $data[] = [
                                            'nome' => $key.' - '.$item->config_tecnicas->cmdb_customer_data->CustomerAddress,
                                            'id' => $key
                                        ];
                                        break;
                                    }
                                }
                            }

                        }
                    }
                }else{
                    foreach ($servicos->result->items as $key => $item){
                        $data[] = [
                            'nome' => $key.' - '.$item->config_tecnicas->cmdb_customer_data->CustomerAddress,
                            'id' => $key
                        ];
                    }
                }
            }
            $statusCode = 200;
            $response = $data;
        }else{
            $statusCode = 404;
            $response = [
                "msg" => "Not Found"
            ];
        }
		return Response::json($response,$statusCode);
	}

	/**
	 * Creates a incident on the API
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postChamado(Request $request){
        $params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
        if(isset($params['assunto']) && isset($params['description']) && isset($params['servico'])){
            $paramsAPI = Array(
                'filter' => [ 'DOCUMENTO' => $documentos, "SVCID" => $params['servico'],"status" => "Habilitado" ],
                'limit' => 1,
                'page' => 1,
                'fields' => ["CONFIG_TECNICAS","SVCID", "ID_CONTRATO", "VALOR", "SVC_DESC", "PROD_DESC", "STATUS", "DATA_INSTALACAO", "DATA_REMOCAO", "DOCUMENTO"]
            );
            $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
            if(isset($servicos->result->info->number_of_rows) && $servicos->result->info->number_of_rows >= 1){
                $documento = reset(reset($servicos->result->items)->produtos)->DOCUMENTO;
                $paramsAPI = Array(
                    'asset' => $params['servico'],
                    'documento' => $documento,
                    'type' => $params['assunto'],
                    'description' => $params['description'],
                    'contact' => Auth::guard('cliente_api')->user()->email,
                    'subject' => 'Novo chamado',
                    'queue' => 'CRM',
                    'note' => (isset($params['nota'])  ? $params['nota'] : '')
                );
                $resposta = $this->ossapi->callAPI( 'services.create_incident', $paramsAPI , $this->auth->result->auth );
                if(isset($resposta->result) && isset($resposta->result->tn)){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Seu chamado foi aberto',
                        'resumo' => $params['assunto'],
                        'tn' => $resposta->result->tn,
                        'url' => url("/cliente/chamados/{$documento}/{$resposta->result->tn}")
                    ];
                    $dado = array(
                        'usuario_id' => Auth::guard('cliente_api')->user()->id,
                        'ip' => $request->ip(),
                        'acao' => 'Criação de um novo chamado - '.$resposta->result->tn,
                        'area' => 'Chamados'
                    );
                    LogUsuario::store($dado);
                }else{
                    $statusCode = 503;
                    $response = [
                        "msg" => "Service Unavailable",
                        'resumo' => 'Ocorreu um erro, tente novamente mais tarde.'
                    ];
                    $dado = array(
                        'usuario_id' => Auth::guard('cliente_api')->user()->id,
                        'ip' => $request->ip(),
                        'acao' => 'Falha ao criar um novo chamado, OSSAPI',
                        'area' => 'Chamados'
                    );
                    LogUsuario::store($dado);
                }
            }else{
                $statusCode = 503;
                $response = [
                    "msg" => "Service Unavailable",
                    'resumo' => 'Ocorreu um erro, tente novamente mais tarde.'
                ];
                $dado = array(
                    'usuario_id' => Auth::guard('cliente_api')->user()->id,
                    'ip' => $request->ip(),
                    'acao' => 'Falha ao criar um novo chamado, OSSAPI',
                    'area' => 'Chamados'
                );
                LogUsuario::store($dado);
            }
        }else { // isset($params['email'])
            $statusCode = 422;
            $response   = [
                "msg" => "Selecionar todos os campos",
                'resumo' => 'Favor preencher todos os campos.'
            ];
        }
		return Response::json($response,$statusCode);
	}
}
