<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 14/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/04/2020
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\Helpers\HasCredentials;
use App\Helpers\GetDados;
use App\LogUsuario;
use App\Manual;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;


class ServicoController extends AreaClienteController
{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct(){
		parent::__construct();
		$this->middleware(array('role:2','cliente_api'));
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/servicos';
	}

	/**
	 * Get the servicos by cliente
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getServicosCliente(Request $request){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento')->toArray();
        $params = $request->all();
        $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 20;
        $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)? $params['page'] : 1;
        $paramsAPI = Array(
            'filter' => [
                'DOCUMENTO' => $documentos,
                'Status' => 'Habilitado'
            ],
            'limit' => $limit,
            'page' => $page,
            'fields' => ["CONFIG_TECNICAS","SVCID", "ID_CONTRATO", "VALOR", "SVC_DESC", "PROD_DESC", "STATUS", "DATA_INSTALACAO", "DATA_REMOCAO", "DOCUMENTO"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        $items = $servicos->result->items;
        $next_page = $servicos->result->info->number_of_rows > $limit ? url("/cliente/servicos?page=".($page+1).'&limit='.$limit) : null;
        $data = [
            'current_page' => $servicos->result->info->current_page,
            'data'=>[],
            'from' => $page,
            'next_page_url' => $next_page,
            'per_page' => $limit,
            'total' => $servicos->result->info->number_of_rows,
        ];
        foreach ($items as $key => $item){
            foreach ($item->produtos as $key2 =>  $produto){
                if($key2 == "Default")
                    continue;
                $data['data'][] = [
                    'id' => $key,
                    'nome' => $produto->SVC_DESC,
                    'DOCUMENTO' => $produto->DOCUMENTO,
                    'status' => $item->config_tecnicas->StatusOper,
                    'endereco' => $item->config_tecnicas->cmdb_customer_data->CustomerAddress,
                    'velocidade' => isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '',
                    'link' => url("/cliente/servicos/{$key}?slug=".str_slug($produto->SVC_DESC))
                ];
            }
        }
        $statusCode = 200;
        $response = $data;
		return Response::json($response, $statusCode);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Http\Response
	 */
	public function getServicobyId(Request $request, $id){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
        $params = $request->all();
        $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 20;
        $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0)? $params['page'] : 1;
        $paramsAPI = Array(
            'filter' => [ 'DOCUMENTO' => $documentos, "SVCID" => $id ],
            'limit' => $limit,
            'page' => $page,
            'fields' => ["CONFIG_TECNICAS", "SVCID", "ID_CONTRATO", "VALOR", "SVC_DESC", "PROD_DESC", "STATUS", "DATA_INSTALACAO", "DATA_REMOCAO", "DOCUMENTO"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        $data = $servicos->result->items;

        $statusCode = 200;
		return Response::json($data, $statusCode);
	}

	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(Request $request){
		$this->beforeReturn();
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualizar',
			'area' => 'Serviços'
		);
		LogUsuario::store($dado);
		return view('site/clientes/servicos/servicos',$this->data);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function detalhe(Request $request , $id){
		$this->beforeReturn();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento')->toArray();
        $params = $request->all();
        $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 20;
        $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0) ? $params['page'] : 1;
        $paramsAPI = Array(
            'filter' => [ 'DOCUMENTO' => $documentos, "SVCID" => $id,"status" => "Habilitado"],
            'limit' => $limit,
            'page' => $page,
            'fields' => ["CONFIG_TECNICAS","SVCID", "ID_CONTRATO", "VALOR", "SVC_DESC", "PROD_DESC", "STATUS", "DATA_INSTALACAO", "DATA_REMOCAO", "DOCUMENTO","ENDERECAMENTO_IP"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        $items = (isset($servicos->result) && isset($servicos->result->items)) ? $servicos->result->items : [];
        $data = $this->data;
        $data['id'] = $id;
        if(isset($servicos->result) && isset($servicos->result->info) && isset($servicos->result->info->number_of_rows) && $servicos->result->info->number_of_rows >= 1){
            foreach ($items as $key => $item){
                if(isset($item->produtos)){
                    $id_contract = false;
                    $contrato = null;
                    $documento = false;
                    $cliente_nome = '';
                    foreach ($item->produtos as $key2 => $produto){
                        if($key2 == 'Default')
                            continue;
                        $periodos = null;
                        $chamadas = false;
                        $grafico_consumo = false;
                        $grafico_voz = false;
                        $logins = false;
                        if(isset($produto->attrs->config_tecnicas)){
                            $enderecamentos = GetDados::getDadosEnderecamentos($produto->attrs->config_tecnicas);
                            $enderecamento_ipv6 = GetDados::getDadosEnderecamentoIPV6($produto->attrs->config_tecnicas);
                            $dados_media = GetDados::getDadosMediaGateway($produto->attrs->config_tecnicas);
                            $dados_fibra = GetDados::getDadosFibraCall($produto->attrs->config_tecnicas);
                            $velocidade = isset($produto->attrs->config_tecnicas->Velocidade) ? $produto->attrs->config_tecnicas->Velocidade : '';
                            $status = isset($produto->attrs->config_tecnicas->StatusOper) ? $produto->attrs->config_tecnicas->StatusOper : '';
                            $manuaisDb = Manual::getByServicoUrl($produto->attrs->config_tecnicas->TipoServico,5);
                            $endereco = $produto->attrs->config_tecnicas->cmdb_customer_data->CustomerAddress;
                        }else{
                            $enderecamentos = GetDados::getDadosEnderecamentos($item->config_tecnicas);
                            $enderecamento_ipv6 = GetDados::getDadosEnderecamentoIPV6($item->config_tecnicas);
                            $dados_media = GetDados::getDadosMediaGateway($item->config_tecnicas);
                            $dados_fibra = GetDados::getDadosFibraCall($item->config_tecnicas);
                            $velocidade = isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '';
                            $status = isset($item->config_tecnicas->StatusOper) ? $item->config_tecnicas->StatusOper : '';
                            $manuaisDb = Manual::getByServicoUrl($item->config_tecnicas->TipoServico,5);
                            $endereco = $item->config_tecnicas->cmdb_customer_data->CustomerAddress;
                        }
                        $manuais = [];
                        if($manuaisDb->count() > 0){
                            foreach ($manuaisDb as $manual){
                                $manuais[] = [
                                    'nome' => $manual->nome,
                                    'url' => $manual->media->media_root->path.$manual->media->nome];
                            }
                        }else{
                            $manuais = false;
                        }

                        if($id_contract !== $produto->ID_CONTRATO){
                            $id_contract = $produto->ID_CONTRATO;
                            $paramsAPIContract = Array(
                                'filter' => ["ID_CONTRACT" => $produto->ID_CONTRATO ],
                                'sort' => ['-asc' => 'ID_UNICIFICADO'],
                                'limit' => 10,
                                'page' => 1,
                                'fields' => ["ID", "ID_CLIENTE", "ID_ENDERECO_CORRESP", "VALOR_SALDO_ATUAL", "STATUS", "DIA_INICIO_DE_CORTE", "ID_CONTRACT", "DOCUMENTO", "VENCIMENTO" ]
                            );
                            $contrato = $this->ossapi->callAPI( 'services.contract_list', $paramsAPIContract , $this->auth->result->auth )->result->items[0];
                        }
                        if(isset($dados_fibra) && $dados_fibra){
                            $paramsAPIComsumption = GetDados::getConsumoParcialParams($produto->SVCID,$contrato);
                            $partial_consumption = $this->ossapi->callAPI( 'services.get_partial_consumption', $paramsAPIComsumption , $this->auth->result->auth );
                            if(isset($partial_consumption->result->consumption))
                                $dados_fibra['consumo_parcial'] = $partial_consumption->result->consumption;
                        }
                        if($dados_fibra !== false)
                            $logins = true;
                        $modulo_consumo = Auth::user()->modulos()->where('id','=',7)->count() >= 1;
                        $modulo_voz = Auth::user()->modulos()->where('id','=',8)->count() >= 1;
                        if($modulo_consumo || $modulo_voz){
                            $paramsGraph = array (
                                'id' => isset($produto->TipoServico) ? $produto->TipoServico : $item->config_tecnicas->TipoServico
                            );
                            $tipoServico = $this->ossapi->callAPI( 'services.service_types', $paramsGraph , $this->auth->result->auth );
                            if(isset( $tipoServico->result) && isset($tipoServico->result->service_types) && isset($tipoServico->result->service_types[0])){
                                if($modulo_consumo && isset($tipoServico->result->service_types[0]->traffic_graph) && boolval($tipoServico->result->service_types[0]->traffic_graph))
                                    $grafico_consumo = true;
                                if($modulo_voz && isset($tipoServico->result->service_types[0]->called_graph) && boolval($tipoServico->result->service_types[0]->called_graph)){
                                    $grafico_voz = true;
                                    $startDate = $produto->DATA_INSTALACAO;
                                    if(isset($contrato)){
                                        $start = \DateTime::createFromFormat('d/m/Y',$startDate);
                                        $end = \DateTime::createFromFormat('d/m/Y', ($contrato->DIA_INICIO_DE_CORTE - 1).'/'.date('m').'/'.date('Y'));
                                        $today = \DateTime::createFromFormat('d/m/Y',date('d/m/Y'));
                                        if($today > $end)
                                            $end->modify('+1month');
                                        $years = 0;
                                        $years += intval($end->format('Y') - $start->format('Y'));
                                        $periodos = [];
                                        $year = \DateTime::createFromFormat('Y-m-d',date('Y-12-31'));
                                        for($i = 0;$i <= $years; $i++){
                                            if($year < $end){
                                                $tempEnd = \DateTime::createFromFormat('Y-m-d',$year->format('Y-m-d'));
                                            }else{
                                                $tempEnd = \DateTime::createFromFormat('Y-m-d',$end->format('Y-m-d'));
                                                $tempEnd->modify('-'.$i.'year');
                                            }
                                            $periodos[] = [
                                                'text' => $end->format('Y') - abs($i),
                                                'intervalo' => (date('Y')-$i).'-01-01*'.$tempEnd->format('Y-m-d')
                                            ];
                                            $year->modify('-1 year');
                                        }
                                        $chamadas = true;
                                    }
                                }
                            }
                        }
                        $dados_servicos = [
                            'id' => $produto->SVCID,
                            'velocidade' => $velocidade,
                            'status' => $status,
                            'data_ativacao' => $produto->DATA_INSTALACAO,
                            'data_vencimento' => $contrato->VENCIMENTO,
                            'periodo_apuracao' => sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE).'-'.sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE-1)
                        ];
                        // Chamados
                        if(HasCredentials::checkRole(3)){
                            $paramsAPIChamados = Array(
                                'DOCUMENTO' => $produto->DOCUMENTO,
                                'asset' => $produto->SVCID,
                                'status' => ["Atualizado", "Não Resolvido", "Aguardando", "Resolvido"],
                                'limit' => 3,
                                'order' => 'order by CreatedTime desc'
                            );
                            $chamadosHtt = $this->ossapi->callAPI( 'services.incident_list', $paramsAPIChamados , $this->auth->result->auth)->result->incident;
                            $chamados = [];
                            foreach ($chamadosHtt as $chamado){
                                $chamados[] = [
                                    'protocolo' => $chamado->ReferenceNumber,
                                    'data_abertura' => date('d/m/Y',strtotime($chamado->CreatedTime)),
                                    'categoria' => $chamado->description,
                                    'status' => $chamado->state,
                                    'link' => url("/cliente/chamados/{$produto->DOCUMENTO}/{$chamado->ReferenceNumber}"),
                                ];
                            }
                        }else
                            $chamados = false;

                        if($documento !== $produto->DOCUMENTO){
                            $documento = $produto->DOCUMENTO;
                            $paramsAPI = array(
                                'documento' => $documento,
                                'fields' => ["ID", "INSCRICAO_MUNICIPAL", "NOME", "DOCUMENTO", "RG_INSCRICAO", "FANTASIA", "EMAIL"]
                            );
                            $cliente = $this->ossapi->callAPI( 'services.get_client', $paramsAPI , $this->auth->result->auth);
                            if(isset($cliente->result->items) && sizeof($cliente->result->items) >= 1)
                                $cliente_nome = $cliente->result->items[0]->NOME;

                        }
                        $current = [
                            'manuais' => $manuais,
                            'chamados' => $chamados,
                            'id' => $produto->SVCID,//$key, // TODO Verificar qual é para aparecer com o Ulisses Junior
                            'nome' => $produto->SVC_DESC,
                            'status' => $status,
                            'endereco' => $endereco,
                            'velocidade' => $velocidade,
                            'empresa' => $cliente_nome,
                            'documento' => GetDados::getDocumentoFormated($produto->DOCUMENTO),
                            'telefones' => 'Não implementado',
                            'dados_servicos' => $dados_servicos,
                            'enderecamentos' => $enderecamentos,
                            'enderecamento_ipv6' => $enderecamento_ipv6,
                            'grafico_consumo' => $grafico_consumo,
                            'dados_fibra' => $dados_fibra,
                            'dados_media' => $dados_media,
                            'grafico_voz' => $grafico_voz,
                            'logins' => $logins,
                            'chamadas' => $chamadas,
                            'periodos' => $periodos,
                        ];
                        if(isset($params['slug']) && $params['slug'] == str_slug($produto->SVC_DESC)){
                            $current['ativo'] = true;
                        }
                        $data['items'][] =  $current;
                    }
                }
            }
        }else{
            $data['items'] = [];
            $data['dados_servicos'] = false;
            $data['enderecamentos'] = false;
            $data['enderecamento_ipv6'] = false;
            $data['grafico_consumo'] = false;
            $data['dados_fibra'] = false;
            $data['dados_media'] = false;
            $data['grafico_voz'] = false;
            $data['logins'] = false;
            $data['chamadas'] = false;
        }
		$this->data = $data;
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualização dos detalhes do serviço - '.$id,
			'area' => 'Serviços'
		);
		LogUsuario::store($dado);
		return view('site/clientes/servicos/detalhe',$this->data);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Http\Response
	 */
	public function getLogins(Request $request , $id){
		$params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $statusCode = 200;
        $limit = isset($params['limit']) ? (is_numeric($params['limit']) ? $params['limit'] : 10) : 10;
        $page = isset($params['page']) ? (is_numeric($params['page']) ? $params['page'] : 1 ) : 1;
        if(isset($params['search'])){
            $search = $params['search'];
            $paramsAPI = Array(
                'fields' => ["username", "email_address", "account_code", "domain", "first_name", "last_name"],
                'filter' => [
                    '-and' => [
                        'contract_number' => $id,
                        '-or'=>[
                            'username' =>['-like'=> '%'.$search.'%'],
                            'email_address' => ['-like' => '%'.$search.'%'],
                            'account_code' =>['-like'=> '%'.$search.'%'],
                            'domain' => ['-like'=> '%'.$search.'%'],
                            'first_name' => ['-like'=> '%'.$search.'%'],
                            'last_name' => ['-like' => '%'.$search.'%']
                        ]
                    ]
                ],
                'limit' => $limit,
                'page' => $page,
            );
        }else{
            $paramsAPI = Array(
                'fields' => ["username", "email_address", "account_code", "domain", "first_name", "last_name"],
                'filter' => [ 'contract_number' => $id ],
                'limit' => $limit,
                'page' => $page,
            );
        }
        $logins = $this->ossapi->callAPI( 'services.get_logins', $paramsAPI , $this->auth->result->auth );
        $items = $logins->result->items;
        $next_page = $logins->result->info->number_of_rows > $limit ? url("/cliente/servicos/logins/".$id."/?page=".($page+1).'&limit='.$limit) : null;
        $data = [
            'current_page' => (int)$logins->result->info->current_page,
            'data'=> [],
            'from' => (int)$page,
            'next_page_url' => $next_page,
            'per_page' => (int)$limit,
            'total' => (int)$logins->result->info->number_of_rows,
        ];
        foreach ($items as $item){
            $data['data'][] = [
                'usuario' => $item->username,
                'dominio' => $item->domain,
                'email' => $item->email_address,
                'nome' => $item->first_name.' '.$item->last_name,
                'conta_mae' => $item->account_code,
            ];
        }
		return Response::json($data, $statusCode);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCalledTraffic(Request $request, $id){
		$params = $request->all();
		$data = [];
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $statusCode = 200;
        $startDate = isset($params['start']) ? $params['start'] : date('01/m/Y 00:00');
        $endDate = isset($params['end']) ? $params['end'] : date('d/m/Y H:i');
        $visao = isset($params['visao']) ? $params['visao'] : 'minutos';
        $custozero = isset($params['custozero']) ? $params['custozero'] : 'sim';
        $consolidacao = isset($params['consolidacao']) ? $params['consolidacao'] : 'hora';
        $paramsAPI = Array(
            'svcid' => $id,
            'start' => $startDate,
            'end' =>  $endDate,
            'visao' => $visao, // minutos|price
            'custozero' => $custozero, // sim|nao
            'consolidacao' => $consolidacao // hora|12horas|24horas
        );
        $consumption = $this->ossapi->callAPI( 'services.get_call_data', $paramsAPI , $this->auth->result->auth );
        if(isset($consumption->result) && !isset($consumption->result->error)){
            $start = \DateTime::createFromFormat('d/m/Y H:i',$consumption->result->start_str);
            $end = \DateTime::createFromFormat('d/m/T H:i', $consumption->result->end_str);
            $dates = $consumption->result->consumovoz->categories;
            $data['short_unidade'] = $consumption->result->consumovoz->short_unit;
            $data['unidade'] = $consumption->result->consumovoz->unit;
            $series = $consumption->result->consumovoz->series;
            $temp = [];
            $int = sizeof($series);
            for ($i = 0; $i < $int; $i++){
                $data['categories'][] = $series[$i]->name;
                $temp[$series[$i]->name] = $series[$i]->data;
            }
            if(!isset($data['categories'])){
                $data['categories'] = [];
            }
            $int = sizeof($dates);
            for ($i = 0; $i < $int;$i++){
                $date = [];
                foreach ($data['categories'] as $category){
                    $date[$category] = $temp[$category][$i];
                }
                if($consolidacao == 'hora') {
                    $tempDate     = \DateTime::createFromFormat( 'Y-m-d H', $dates[ $i ] );
                    $date['DATE'] = $tempDate->format( "d/m/Y H:i" );
                }
                if($consolidacao == '12horas'){
                    $dates[$i] = str_replace('AM', 00, $dates[$i]);
                    $dates[$i] = str_replace('PM', 12, $dates[$i]);
                    $tempDate = \DateTime::createFromFormat('Y-m-d H',$dates[$i]);
                    $date['DATE'] = $tempDate->format("d/m/Y H:i");
                }
                if($consolidacao == '24horas'){
                    $tempDate = \DateTime::createFromFormat('Y-m-d',$dates[$i]);
                    $date['DATE'] = $tempDate->format("d/m/Y");
                }
                $data['data'][] = $date;
            }
            if(!isset($data['data'])){
                $data['data'] = [];
            }
        }
		return Response::json($data, $statusCode);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @return \Illuminate\Http\Response
	 */
	public function getServiceTraffic(Request $request, $id){
		$params = $request->all();
		$data = [];
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $statusCode = 200;
        $startDate = isset($params['start']) ? $params['start'] : date('Y-m-01 00:00:01');
        $endDate = isset($params['end']) ? $params['end'] : date('Y-m-d H:i:s');
        $paramsAPI = Array(
            'svcid' => $id,
            'start' => strtotime($startDate),
            'end' =>  strtotime($endDate)
        );
        $consumption = $this->ossapi->callAPI( 'services.get_service_traffic_data', $paramsAPI , $this->auth->result->auth );
        if(isset($consumption->result) && !isset($consumption->result->error)){
            $start = \DateTime::createFromFormat('d/m/Y H:i',$consumption->result->start_str);
            $input = $consumption->result->input;
            $output = $consumption->result->output;
            $int = sizeof($input);
            for ($i = 0; $i < $int;$i++){
                $data[] =
                    [
                        'Downlink' => $input[$i],
                        'Uplink' => $output[$i],
                        'date' => $start->format('d/m/Y H:i')
                    ];
                $minutes = 5;
                $start->modify('+'.$minutes.' minutes');
            }
        }
		return Response::json($data, $statusCode);
	}

	/**
	 * @param Request $request
	 * @param string $id
	 * @param string $url
	 * @return \Illuminate\Http\Response
	 */
	public function getCallDetailed(Request $request , $id, $url){
		$params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $paramsAPI = [
            'svcid' => $id,
            'filename' => $url
        ];
        $file = $this->ossapi->callAPI( 'services.get_call_file', $paramsAPI , $this->auth->result->auth );
        if(isset($file->result) && isset($file->result->file)){
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename="'.$file->result->filename.'"');
            echo base64_decode($file->result->file);
        }else{
            echo "Arquivo não encontrado, contate o suporte";
        }
        die();
	}

	public function getCallDetailedTime(Request $request, $id){
		$params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $limit = isset($params['limit']) ? (is_numeric($params['limit']) ? $params['limit'] : 10) : 10;
        $page = isset($params['page']) ? (is_numeric($params['page']) ? $params['page'] : 1 ) : 1;
        $end = \DateTime::createFromFormat('d/m/Y H:i:s',date('d/m/Y H:i:s'));
        $start = \DateTime::createFromFormat('d/m/Y H:i:s',date('d/m/Y 00:00:00'));
        $start->modify('-30 day');
        if(isset($params['search'])){
            $search = $params['search'];
            $paramsAPI = Array(
                'fields' => ['id', 'caller_id', 'call_start_time', 'price', 'service_id', 'duration', 'callee_id', 'call_type'],
                'filter' => [
                    'service_id' => $id,
                    '-and' => [
                        ['call_start_time' => ['>=' => $start->format('Y-m-d H:i:s')]],
                        ['call_start_time' => ['<=' =>  $end->format('Y-m-d H:i:s') ]],
                        ['-or'=>
                            [
                                'caller_id' =>['-like'=> '%'.$search.'%'],
                                'price::varchar' => ['-like' => '%'.$search.'%'], // CAMPO NÃO ESTÁ COMO VARCHAR NO BANCO DA xxxx
                                'service_id' =>['-like'=> '%'.$search.'%'],
                                'duration::varchar' => ['-like'=> '%'.$search.'%'], // CAMPO NÃO ESTÁ COMO VARCHAR NO BANCO DA xxxx
                                'callee_id' => ['-like'=> '%'.$search.'%'],
                                'call_type' => ['-like' => '%'.$search.'%'],
                                'call_start_time::varchar' => ['-like' => '%'.$search.'%']
                            ]
                        ]
                    ]
                ],
                'limit' => intval($limit),
                'page' => intval($page),
            );
        }else{
            $paramsAPI = Array(
                'fields' => ['id', 'caller_id', 'call_start_time', 'price', 'service_id', 'duration', 'callee_id', 'call_type'],
                'filter' => [
                    'service_id' => $id,
                    '-and' => [
                        ['call_start_time' => ['>=' => $start->format('Y-m-d H:i:s')]],
                        ['call_start_time' => ['<=' =>  $end->format('Y-m-d H:i:s') ]]
                    ]
                ],
                'limit' => intval($limit),
                'page' => intval($page),
                'sort' => ['-asc' => 'call_start_time'],
            );
        }
        $callDetailed = $this->ossapi->callAPI( 'services.get_call_detailed', $paramsAPI , $this->auth->result->auth );
        if(isset($callDetailed->result) && isset($callDetailed->result->info) && isset($callDetailed->result->items)){
            $statusCode = 200;
            $next_page = $callDetailed->result->info->number_of_rows > $limit ? url("/cliente/servicos/".$id."/call-detailed-time?page=".($page+1).'&limit='.$limit) : null;
            $data = [
                'current_page' => $callDetailed->result->info->current_page,
                'next_page_url' => $next_page,
                'per_page' => $limit,
                'total' => $callDetailed->result->info->number_of_rows,
                'data' => $callDetailed->result->items
            ];
            $response = $data;
        }else{
            $statusCode = 503;
            $response = [
                'msg' => 'Service Unavailable'
            ];
        }
		return Response::json($response, $statusCode);
	}

	public function getCalls(Request $request, $id){
		$params = $request->all();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $dates = explode('*',$params['intervalo']);
        $paramsAPI = array(
            'svcid' => $id,
            'start' => $dates[0],
            'end' => $dates[1]
        );
        $data = [];
        $fileList = $this->ossapi->callAPI( 'services.get_call_file_list', $paramsAPI , $this->auth->result->auth );
        if(isset($fileList->result) && isset($fileList->result->items)){
            foreach ( $fileList->result->items as $file ) {
                $data['chamadas'][] = [
                    'text' => 'Período de '.$file->start.' a '.$file->end,
                    'csv' => url('cliente/servicos/'.$id.'/call-detailed/'.$file->csv),
                    'xls' => url('cliente/servicos/'.$id.'/call-detailed/'.$file->xlsx),
                ];
            }
        }
        $statusCode = 200;
        $response = $data;
		return Response::json($response,$statusCode);
	}
}
