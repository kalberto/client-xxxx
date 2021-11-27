<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 14/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\Helpers\HasCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class DashboardController extends AreaClienteController
{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct(){
		$this->middleware('role:1,cliente_api');
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente';
	}

	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$this->beforeReturn();
        $this->data['servicos'] = HasCredentials::checkRole(2);
        $this->data['chamados'] = HasCredentials::checkRole(3);
        $this->data['faturas'] = HasCredentials::checkRole(4);
        $this->data['contratos'] = HasCredentials::checkRole(5);
        $this->data['contato'] = HasCredentials::checkRole(6);
		return view('site.clientes.dashboard',$this->data);
	}

	/**
	 * Get the servicos by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getServicosCliente(Request $request){
        if(HasCredentials::checkRole(2)) {
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos      = Auth::guard( 'cliente_api' )->user()->organizacao()->first()->documentos()->pluck('documento');
            $params    = $request->all();
            $limit     = 2;
            $page      = 1;
            $paramsAPI = Array(
                'filter' => [ 'DOCUMENTO' => $documentos,'Status' => 'Habilitado' ],
                'limit'  => $limit,
                'page'   => $page,
                'fields' => [
                    "CONFIG_TECNICAS",
                    "SVCID",
                    "ID_CONTRATO",
                    "VALOR",
                    "SVC_DESC",
                    "PROD_DESC",
                    "STATUS",
                    "DATA_INSTALACAO",
                    "DATA_REMOCAO",
                    "DOCUMENTO"
                ]
            );
            $servicos  = $this->ossapi->callAPI( 'services.list', $paramsAPI, $this->auth->result->auth );
            $items     = $servicos->result->items;
            $data      = [];
            foreach ( $items as $key => $item ) {
                foreach ( $item->produtos as $key2 => $produto ) {
                    if($key2 == "Default")
                        continue;
                    $data['data'][] = [
                        'id'               => $key,
                        'nome'             => $produto->SVC_DESC,
                        'DOCUMENTO'        => $produto->DOCUMENTO,
                        'velocidade'       => isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '',
                        'endereco'         => $item->config_tecnicas->cmdb_customer_data->CustomerAddress,
                        'status'           => $item->config_tecnicas->StatusOper,
                        'data_contratacao' => isset($item->config_tecnicas->DataAtivacao) ? date( 'd/m/Y', strtotime( $item->config_tecnicas->DataAtivacao ) ) : '',
                        'link'             => url( "/cliente/servicos/{$key}?slug=".str_slug($produto->SVC_DESC))
                    ];
                }
            }
            $statusCode = 200;
            $response   = $data;
        }else{
            $statusCode = 403;
            $response = [
                "data" => []
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the chamados by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getChamadosCliente(Request $request){
        if(HasCredentials::checkRole(3)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $tempData = ['chamados' => []];
            $tempOrd = [];
            foreach ($documentos as $documento){
                $paramsAPI = Array(
                    'DOCUMENTO' => $documento,
                    'status'=> ["Atualizado", "Não Resolvido", "Aguardando"],
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
        }else{
            $statusCode = 403;
            $response = [
                'chamados' => []
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the faturas by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getFaturasCliente(Request $request){
        if(HasCredentials::checkRole(4)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $params = $request->all();
            $limit = 1;
            $page = 1;
            $paramsAPI = Array(
                'sort'=> [['-desc'=> ['ANO_VENCIMENTO','MES_VENCIMENTO','DATA_VENCIMENTO']]],
                'filter' => [
                    'DOCUMENTO' => $documentos,
                    'STATUS' => 'Pendente'
                ],
                'limit' => $limit,
                'page' => $page,
                'fields' => ['DOCUMENTO', 'ANO_VENCIMENTO', 'STATUS', 'FATURA', 'VALOR_SALDO_ATUAL', 'CONTRACT', 'DATA_VENCIMENTO', 'MES_VENCIMENTO', 'VALOR', 'ID_CONTRATO', 'HASH', 'URL']
            );
            $data = [
                'faturas' => [],
                'paginate' => false
            ];
            $faturas = $this->ossapi->callAPI( 'services.invoice_list', $paramsAPI , $this->auth->result->auth );
            if(isset($faturas->result->info->number_of_rows)){
                $limit = $faturas->result->info->number_of_rows;
                $paramsAPI = Array(
                    'sort'=> [['-desc'=> ['ANO_VENCIMENTO','MES_VENCIMENTO','DATA_VENCIMENTO']]],
                    'filter' => [
                        'DOCUMENTO' => $documentos,
                        'STATUS' => 'Pendente'
                    ],
                    'limit' => $limit,
                    'page' => $page,
                    'fields' => ['DOCUMENTO', 'ANO_VENCIMENTO', 'STATUS', 'FATURA', 'VALOR_SALDO_ATUAL', 'CONTRACT', 'DATA_VENCIMENTO', 'MES_VENCIMENTO', 'VALOR', 'ID_CONTRATO', 'HASH', 'URL']
                );
                $faturas = $this->ossapi->callAPI( 'services.invoice_list', $paramsAPI , $this->auth->result->auth );
                if(isset($faturas->result->items)){
                    foreach ($faturas->result->items as $fatura){
                        $data['faturas'][] = [
                            'contrato'      => $fatura->CONTRACT,
                            'DOCUMENTO'     => $fatura->DOCUMENTO,
                            'fatura'        => $fatura->FATURA,
                            'vencimento'    => $fatura->DATA_VENCIMENTO,
                            'valor'         => number_format($fatura->VALOR,2,',','.'),
                            'status'        => $fatura->STATUS,
                            'link'          => $fatura->URL
                        ];
                    }
                }
            }
            $number_of_faturas = sizeof($data['faturas']);
            if($number_of_faturas < 3){
                $paramsAPI = Array(
                    'sort'=> [['-desc'=> ['ANO_VENCIMENTO','MES_VENCIMENTO','DATA_VENCIMENTO']]],
                    'filter' => [
                        'DOCUMENTO' => $documentos,
                        '-and' => [
                            'STATUS' => [
                                '-not_like' => 'Pendente'
                            ]
                        ]
                    ],
                    'limit' => 3 - $number_of_faturas,
                    'page' => $page,
                    'fields' => ['DOCUMENTO', 'ANO_VENCIMENTO', 'STATUS', 'FATURA', 'VALOR_SALDO_ATUAL', 'CONTRACT', 'DATA_VENCIMENTO', 'MES_VENCIMENTO', 'VALOR', 'ID_CONTRATO', 'HASH', 'URL']
                );
                $faturas = $this->ossapi->callAPI( 'services.invoice_list', $paramsAPI , $this->auth->result->auth );
                if(isset($faturas->result->items)){
                    foreach ($faturas->result->items as $fatura){
                        $data['faturas'][] = [
                            'contrato'      => $fatura->CONTRACT,
                            'DOCUMENTO'     => $fatura->DOCUMENTO,
                            'fatura'        => $fatura->FATURA,
                            'vencimento'    => $fatura->DATA_VENCIMENTO,
                            'valor'         => number_format($fatura->VALOR,2,',','.'),
                            'status'        => $fatura->STATUS,
                            'link'          => $fatura->URL
                        ];
                    }
                }
            }else{
                $fats = $data['faturas'];
                $next_page = $faturas->result->info->number_of_rows > $limit ? url("/cliente/faturas/list?page=".($page+1).'&limit='.$limit) : null;
                $data = [
                    'current_page' => $faturas->result->info->current_page,
                    'faturas'=>$fats,
                    'from' => $page,
                    'next_page_url' => $next_page,
                    'per_page' => $limit,
                    'total' => $faturas->result->info->number_of_rows,
                    'paginate' => true
                ];
            }
            $statusCode = 200;
            $response = $data;
        }else{
            $statusCode = 403;
            $response = [
                'faturas' => [],
                'paginate' => false,
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the contratos by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getContratosCliente(Request $request){
        if(HasCredentials::checkRole(5)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $params = $request->all();
            $limit = 3;
            $page = 1;
            $paramsAPI = Array(
                'sort'=>['-asc' => 'VENCIMENTO'],
                'filter' => [
                    '-and' => [
                        'CONTRACT' => ['-not_like' => '%-CANCELADO'],
                        'DOCUMENTO' => $documentos,
                    ]
                ],
                'limit'  => $limit,
                'page'   => $page,
                'fields' => [
                    'ID_CONTRACT', 'ID_CLIENTE', 'ID_ENDERECO_CORRESP', 'VALOR_SALDO_ATUAL', 'STATUS', 'DIA_INICIO_DE_CORTE', 'CONTRACT', 'DOCUMENTO', 'VENCIMENTO','MENSALIDADE'
                ]
            );
            $contratos = $this->ossapi->callAPI( 'services.contract_list', $paramsAPI , $this->auth->result->auth )->result->items;
            $data = [];
            foreach ($contratos as $contrato){
                $data['contratos'][] = [
                    'contrato' => $contrato->CONTRACT,
                    'vencimento' => $contrato->VENCIMENTO,
                    'mensalidade' => number_format($contrato->MENSALIDADE,2,',','.'),//reset($faturas)->VALOR
                    'faturamento' => $contrato->DIA_INICIO_DE_CORTE -1,
                    'DOCUMENTO' => $contrato->DOCUMENTO,
                    'status' => $contrato->STATUS,
                    'link' => 'Não implementado',
                ];
            }
            $statusCode = 200;
            $response = $data;
        }else{
            $statusCode = 403;
            $response = [
                'contratos' => []
            ];
        }
		return Response::json($response,$statusCode);
	}
}
