<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:21
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\LogUsuario;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use function Symfony\Component\HttpKernel\Tests\controller_func;

class ContatoController extends AreaClienteController{


	public function __construct() {
		parent::__construct();
		$this->middleware(array('role:6','cliente_api'));
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/contato';
	}

	public function index(){
		$this->beforeReturn();
		return view('site.clientes.contato.contato',$this->data);
	}

	/**
	 * Get the Category by Product
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getRightNowFields(Request $request){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $paramsAPI = Array();
        $assuntos = $this->ossapi->callAPI( 'services.get_rightnow_fields', $paramsAPI , $this->auth->result->auth );
        $data = [];
        if(isset($assuntos->result)){
            $data['assuntos'] = [];
            foreach ($assuntos->result as $key => $item){
                $data['assuntos'][$item->name] = [
                    'name' => $item->name,
                    'id' => $item->id,
                    'descriptions' => $item->description
                ];
            }
        }
        $statusCode = 200;
        $response = $data;
		return Response::json($response,$statusCode);
	}

	/**
	 * Get the contact list by DOCUMENTO
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getContactList(Request $request){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
        $paramsAPI = Array(
            'DOCUMENTO' => $documentos
        );
        $contatos = $this->ossapi->callAPI( 'services.contact_list', $paramsAPI , $this->auth->result->auth );
        $data = [];
        if(isset($contatos->result) && isset($contatos->result->contacts)){
            $statusCode = 200;
            foreach ($contatos->result->contacts as $item){
                $data[] = [
                    'email' => $item->email
                ];
            }
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
	 * Get the service list by DOCUMENTO
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getServiceList(Request $request){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento');
        $paramsAPI = Array(
            'filter' => [ 'DOCUMENTO' => $documentos,"status" => "Habilitado" ],
            'page' => 1,
            'limit' => 0,
            'fields' => ["SVCID"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        if(isset($servicos->result) && isset($servicos->result->info) && $servicos->result->info->number_of_rows > 0){
            $data = [];
            $paramsAPI['limit'] = $servicos->result->info->number_of_rows;
            $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
            foreach ($servicos->result->items as $key => $item){
                $data[] = $key;
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
	public function postContact(Request $request){
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
                if(isset($resposta->result->tn)){
                    $dado = array(
                        'usuario_id' => Auth::guard('cliente_api')->user()->id,
                        'ip' => $request->ip(),
                        'acao' => 'Criação de um novo chamado - '.$resposta->result->tn,
                        'area' => 'Contato'
                    );
                    LogUsuario::store($dado);
                    $statusCode = 200;
                    $response = [
                        "msg" => "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve."
                    ];
                }else{
                    $dado = array(
                        'usuario_id' => Auth::guard('cliente_api')->user()->id,
                        'ip' => $request->ip(),
                        'acao' => 'Falha ao criar um novo chamado, OSSAPI',
                        'area' => 'Contato'
                    );
                    LogUsuario::store($dado);
                    $statusCode = 503;
                    $response = [
                        "msg" => "Service Unavailable"
                    ];
                }
            }else{
                $dado = array(
                    'usuario_id' => Auth::guard('cliente_api')->user()->id,
                    'ip' => $request->ip(),
                    'acao' => 'Falha ao criar um novo chamado, OSSAPI',
                    'area' => 'Contato'
                );
                LogUsuario::store($dado);
                $statusCode = 503;
                $response = [
                    "msg" => "Service Unavailable"
                ];
            }
        }else {
            $statusCode = 422;
            $response   = [
                "msg" => "Selecionar todos os campos"
            ];
        }
		return Response::json($response,$statusCode);
	}
}