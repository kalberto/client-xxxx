<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 06/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Configuracao;
use App\Http\Requests\Configuracao\ConfiguracaoRequest;
use App\LogAdministrador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class ConfiguracaoController extends Controller
{

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:6' );
	}

	/**
	 * Display the specified resource.
	 * RF00
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
        $configuracao = Configuracao::firstOrFail();
        if(isset($configuracao)){
            $statusCode = 200;
            $response = $configuracao;
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar as configurações'
            ];
        }
		return Response::json($response,$statusCode);
	}

    /**
     * Update the specified resource in storage.
     * RF0030
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(($configuracao = Configuracao::first()) != null){
            $updateRequest = new ConfiguracaoRequest();
            $data = $request->all();
            $validate = $updateRequest->validar($data);
            if(!$validate->fails()){
                $log = array(
                    'administrador_id' => Auth::user()->id,
                    'registro_id' => $configuracao->id,
                    'tabela' => 'configuracao',
                    'tipo' => 'update',
                    'ip' => $request->ip()
                );
                LogAdministrador::store($log);
                if($configuracao->edit($data)){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Configurações editadas com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar as configurações'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar as configurações',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar as configurações'
            ];
        }
	    return Response::json($response, $statusCode);
    }
}
