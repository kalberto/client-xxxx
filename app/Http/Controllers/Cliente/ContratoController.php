<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:22
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\Helpers\HasCredentials;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ContratoController extends AreaClienteController{
	public function __construct() {
		parent::__construct();
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/contratos';
	}

	public function index(){
		$this->beforeReturn();
		return view('site.clientes.contratos.contratos',$this->data);
	}

	public function getContratosCliente(Request $request){
        if(HasCredentials::checkRole(5)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $params = $request->all();
            $limit = isset($params['limit']) ? (is_numeric($params['limit']) ? $params['limit'] : 3) : 3;
            $page = isset($params['page']) ? (is_numeric($params['page']) ? $params['page'] : 1 ) : 1;
            $paramsAPI = Array(
                'sort'=>['-asc' => 'VENCIMENTO'],
                'filter' => [
                    '-and' => [
                        'CONTRACT' => ['-not_like' => '%-CANCELADO'],
                        'DOCUMENTO' => $documentos
                    ]
                ],
                'limit'  => $limit,
                'page'   => $page,
                'fields' => [
                    'ID_CONTRACT', 'ID_CLIENTE', 'ID_ENDERECO_CORRESP', 'VALOR_SALDO_ATUAL', 'STATUS', 'DIA_INICIO_DE_CORTE', 'CONTRACT', 'DOCUMENTO', 'VENCIMENTO'
                ]
            );
            $contratos = $this->ossapi->callAPI( 'services.contract_list', $paramsAPI , $this->auth->result->auth );
            if(isset($contratos->result) && isset($contratos->result->items)){
                $next_page = $contratos->result->info->number_of_rows > $limit ? url("/cliente/contratos/vigentes?page=".($page+1).'&limit='.$limit) : null;
                $data = [
                    'current_page' => $contratos->result->info->current_page,
                    'from' => $page,
                    'next_page_url' => $next_page,
                    'per_page' => $limit,
                    'total' => $contratos->result->info->number_of_rows,
                ];
                foreach ($contratos->result->items as $contrato){
                    $data['contratos_antigos'][] = [
                        'contrato' => $contrato->CONTRACT,
                        'vencimento' => $contrato->VENCIMENTO,
                        'inicio' => $contrato->DIA_INICIO_DE_CORTE,
                        'status' => $contrato->STATUS,
                        'link' => 'Não implementado',
                        'saldo_atual' => $contrato->VALOR_SALDO_ATUAL,
                    ];
                }
                $statusCode = 200;
                $response = $data;
            }else{
                $statusCode = 404;
                $response = [
                    'msg' => 'Not Found'
                ];
            }
        }else{
            $statusCode = 403;
            $response = [
                'contratos' => []
            ];
        }
		return Response::json($response,$statusCode);
	}

	public function getContratosVigentes(Request $request){
        if(HasCredentials::checkRole(5)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $params = $request->all();
            $limit = isset($params['limit']) ? (is_numeric($params['limit']) ? $params['limit'] : 10) : 10;
            $page = isset($params['page']) ? (is_numeric($params['page']) ? $params['page'] : 1 ) : 1;
            $paramsAPI = Array(
                'sort'=>['-asc' => 'VENCIMENTO'],
                'filter' => [
                    '-and' => [
                        'DOCUMENTO' => $documentos,
                        'STATUS' => ['-like' => 'Habilitado']
                    ]
                ],
                'limit'  => $limit,
                'page'   => $page,
                'fields' => [
                    'ID_CONTRACT', 'ID_CLIENTE', 'ID_ENDERECO_CORRESP', 'VALOR_SALDO_ATUAL', 'STATUS', 'DIA_INICIO_DE_CORTE', 'CONTRACT', 'DOCUMENTO', 'VENCIMENTO', 'MENSALIDADE'
                ]
            );
            $contratos = $this->ossapi->callAPI( 'services.contract_list', $paramsAPI , $this->auth->result->auth );
            if(isset($contratos->result) && isset($contratos->result->items)){
                $next_page = $contratos->result->info->number_of_rows > $limit ? url("/cliente/contratos/vigentes?page=".($page+1).'&limit='.$limit) : null;
                $data = [
                    'current_page' => $contratos->result->info->current_page,
                    'contratos_vigentes'=>[],
                    'from' => $page,
                    'next_page_url' => $next_page,
                    'per_page' => $limit,
                    'total' => $contratos->result->info->number_of_rows,
                ];
                foreach ($contratos->result->items as $contrato){
                    $data['contratos_vigentes'][] = [
                        'DOCUMENTO' => $contrato->DOCUMENTO,
                        'contrato' => $contrato->CONTRACT,
                        'vencimento' => $contrato->VENCIMENTO,
                        'mensalidade' => number_format($contrato->MENSALIDADE,2,',','.'),//$ultima_fatura,
                        'faturamento' => $contrato->DIA_INICIO_DE_CORTE -1,
                        'status' => $contrato->STATUS,
                        'link' => 'Não implementado',
                        'saldo_atual' => $contrato->VALOR_SALDO_ATUAL,
                    ];
                }
                $statusCode = 200;
                $response = $data;
            }else{
                $statusCode = 404;
                $response = [
                    'msg' => 'Not Found'
                ];
            }
        }else{
            $statusCode = 403;
            $response = [
                'contratos_vigentes' => []
            ];
        }
		return Response::json($response,$statusCode);
	}
}