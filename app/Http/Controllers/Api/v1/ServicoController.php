<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/11/2017
 * Time: *
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 06/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Helpers\OSSAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;
use function Sodium\library_version_minor;

class ServicoController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('role:4');
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
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $paramsAPI = (object)array();
        $new = $this->ossapi->callAPI( 'services.service_types', $paramsAPI, $this->auth->result->auth );
        $statusCode = 200;
        $response = $new->result->service_types;
        return Response::json($response, $statusCode);
		Return Response::json($response, $statusCode);
	}

	public function getByDocumento($documento){
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $data = [];
        if(isset($documento)){
            $documento = str_replace('.','',$documento);
            $documento = str_replace('/','',$documento);
            $documento = str_replace('-','',$documento);
            $documento = str_replace(' ','',$documento);
            $paramsAPI = Array(
                'filter' => [ 'DOCUMENTO' => $documento,"status" => "Habilitado" ],
                'limit' => 1,
                'page' => 1,
                'fields' => ["CONFIG_TECNICAS","SVCID"]
            );
            $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI, $this->auth->result->auth );
            if(isset($servicos ->result) && isset($servicos ->result->items)){
                $paramsAPI = Array(
                    'filter' => [ 'DOCUMENTO' => $documento,"status" => "Habilitado" ],
                    'limit' => $servicos ->result->info->number_of_rows,
                    'page' => 1,
                    'fields' => ["CONFIG_TECNICAS","SVCID"]
                );
                $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI, $this->auth->result->auth );
                if(isset($servicos ->result) && isset($servicos ->result->items)){
                    foreach ($servicos ->result->items as $key => $servico){
                        if(isset($servico->config_tecnicas)){
                            $data[] = [
                                'SVCID' => $key,
                                'endereco' => $servico->config_tecnicas->cmdb_customer_data->CustomerAddress
                            ];
                        }else{
                            $data[] = [
                                'SVCID' => $key,
                                'endereco' => 'Não consta.'
                            ];
                        }

                    }
                }
            }
        }
        $statusCode = 200;
        $response = $data;
        return Response::json($response, $statusCode);
	}

}
