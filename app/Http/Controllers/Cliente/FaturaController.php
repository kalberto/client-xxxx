<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:12
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Cliente;


use App\Helpers\HasCredentials;
use App\Helpers\OSSAPI;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class FaturaController extends AreaClienteController{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->middleware(array('role:3','cliente_api'));
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/faturas';
	}

	public function index(){
		$this->beforeReturn();
		return view('site.clientes.faturas.faturas',$this->data);
	}

	/**
	 * Get the ultimas faturas by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getLastFaturasCliente(Request $request){
        if(HasCredentials::checkRole(4)){
            if(!$this->authenticate())
                throw new Exception('API ERROR');
            $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
            $params = $request->all();
            $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 10;
            $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0) ? $params['page'] : 1;
            $paramsAPI = Array(
                'sort'=> [['-desc'=> ['ANO_VENCIMENTO','MES_VENCIMENTO','DATA_VENCIMENTO']]],
                'filter' => [
                    '-and' => [
                        'DOCUMENTO' => $documentos,
                        'STATUS' => ['-like' => 'Pendente'] //Cancelado, Quitado, BaixaCriticaNaoResolvida, Pendente, Baixa, Negociado
                    ],
                ],
                'limit' => $limit,
                'page' => $page,
                'fields' => ['DOCUMENTO', 'ANO_VENCIMENTO', 'STATUS', 'FATURA', 'VALOR_SALDO_ATUAL', 'CONTRACT', 'DATA_VENCIMENTO', 'MES_VENCIMENTO', 'VALOR', 'ID_CONTRATO', 'HASH', 'URL']
            );
            $faturas = $this->ossapi->callAPI( 'services.invoice_list', $paramsAPI , $this->auth->result->auth );
            if(isset($faturas->result) && isset($faturas->result->items)){
                $next_page = $faturas->result->info->number_of_rows > $limit ? url("/cliente/faturas/last?page=".($page+1).'&limit='.$limit) : null;
                $data = [
                    'current_page' => $faturas->result->info->current_page,
                    'faturas'=>[],
                    'from' => $page,
                    'next_page_url' => $next_page,
                    'per_page' => $limit,
                    'total' => $faturas->result->info->number_of_rows,
                ];
                foreach ($faturas->result->items as $fatura){
                    $data['faturas'][] = [
                        'DOCUMENTO' => $fatura->DOCUMENTO,
                        'contrato' => $fatura->CONTRACT,
                        'fatura' => $fatura->FATURA,
                        'vencimento' => $fatura->DATA_VENCIMENTO,
                        'valor' => number_format($fatura->VALOR,2,',','.'),
                        'status' => $fatura->STATUS,
                        'link' => $fatura->URL
                    ];
                }
                $statusCode = 200;
                $response = $data;
            }else{
                $statusCode = 404;
                $response = [
                    'error' => 'Not found'
                ];
            }
        }else{
            $statusCode = 403;
            $response = [
                'faturas' => []
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
            $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 10;
            $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0) ? $params['page'] : 1;
            $startDate = isset($params['start']) ? $params['start'] : false;
            $endDate = isset($params['end']) ? $params['end'] : false;
            $paramsAPI = array(
                'sort'=> [['-desc'=> ['ANO_VENCIMENTO','MES_VENCIMENTO','DATA_VENCIMENTO']]],
                'filter' => [
                    '-and' => [
                        'DOCUMENTO' => $documentos,
                        'STATUS' => ['-not_like' => 'Pendente'], //Cancelado, Quitado, BaixaCriticaNaoResolvida, Pendente, Baixa, Negociado,
                    ],
                ],
                'limit' => $limit,
                'page' => $page,
                'fields' => ['DOCUMENTO', 'ANO_VENCIMENTO', 'STATUS', 'FATURA', 'VALOR_SALDO_ATUAL', 'CONTRACT', 'DATA_VENCIMENTO', 'MES_VENCIMENTO', 'VALOR', 'ID_CONTRATO', 'HASH', 'URL']
            );
            if($startDate != false){
                $paramsAPI['filter']['-and']['DATA_VENC']['>='] = date('Y-m-d',strtotime($startDate));
            }
            if($endDate != false){
                $paramsAPI['filter']['-and']['DATA_VENC']['<='] = date('Y-m-d',strtotime($endDate));
            }
            $faturas = $this->ossapi->callAPI( 'services.invoice_list', $paramsAPI , $this->auth->result->auth );
            if(isset($faturas->result) && isset($faturas->result->items)){
                $next_page = $faturas->result->info->number_of_rows > $limit ? url("/cliente/faturas/list?page=".($page+1).'&limit='.$limit) : null;
                $data = [
                    'current_page' => $faturas->result->info->current_page,
                    'faturas'=>[],
                    'from' => $page,
                    'next_page_url' => $next_page,
                    'per_page' => $limit,
                    'total' => $faturas->result->info->number_of_rows,
                ];
                foreach ($faturas->result->items as $fatura){
                    $data['faturas'][] = [
                        'DOCUMENTO' => $fatura->DOCUMENTO,
                        'contrato' => $fatura->CONTRACT,
                        'fatura' => $fatura->FATURA,
                        'vencimento' => $fatura->DATA_VENCIMENTO,
                        'valor' => number_format($fatura->VALOR,2,',','.'),
                        'status' => $fatura->STATUS,
                        'link' => $fatura->URL
                    ];
                }
                $statusCode = 200;
                $response = $data;
            }else{
                $statusCode = 404;
                $response = [
                    'error' => 'Not found'
                ];
            }
        }else{
            $statusCode = 403;
            $response = [
                'faturas' => []
            ];
        }
		return Response::json($response, $statusCode);
	}
}