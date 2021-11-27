<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 09/11/2017
 * Time: 09:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 13/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Request\Manual\ManualRequest;
use App\LogAdministrador;
use App\Manual;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class ManualController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('role:4');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $request->all();
        $manualRequest = new ManualRequest();
        $validate = $manualRequest->validar($data);
        if(!$validate->fails()){
            if(Manual::store($data, $request->ip())){
                $statusCode = 200;
                $response = [
                    'msg' => 'Manual cadastrado com sucesso!'
                ];
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao salvar o manual'
                ];
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao salvar o manual',
                'error_validate' => $validate->errors()->all(),
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Manual-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request,$id)
	{
		//media - traz a media ou não
		$params = $request->all();
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar esse manual'
        ];
        if(isset($id) && is_numeric($id)){
            if(isset($params['media']) && $params['media'] == true)
                $manual = Manual::with('media.media_root')->find($id);
            else
                $manual = Manual::find($id);
            if(isset($manual)){
                $statusCode = 200;
                $response = $manual;
            }
        }
		return Response::json($response,$statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Manual-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
        if(isset($id) && is_numeric($id) && Manual::find($id) != null){
            $data = $request->all();
            $manualRequest = new ManualRequest();
            $validate = $manualRequest->validar($data);
            if(!$validate->fails()){
                if(Manual::edit($id, $data)){
                    $data = array(
                        'administrador_id' => Auth::user()->id,
                        'registro_id' => $id,
                        'tabela' => 'manual',
                        'tipo' => 'update',
                        'ip' => $request->ip()
                    );
                    LogAdministrador::store($data);
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Manual editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o manual'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o manual',
                    'error_validate' => $validate->errors()->all(),
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar essa notícia'
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Remove the specified resource from storage.
	 * * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Manual-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id)
	{
		if($id != null && is_numeric($id) && ($manual = Manual::find($id)) != null){
			if($manual->delete()){
				$data = array(
					'administrador_id' => Auth::user()->id,
					'registro_id' => $id,
					'tabela' => 'manual',
					'tipo' => 'delete',
					'ip' => $request->ip()
				);
				LogAdministrador::store($data);
				$statusCode = 200;
				$response = [
					'msg' => 'Manual deletado com sucesso!'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o manual!'
				];
			}
		}
		else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse manual.'
			];
		}
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the manuais by servico
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string $url
	 * @return \Illuminate\Http\Response
	 */
	public function getManuaisServico(Request $request, $url){
        $manuais = Manual::getByServico($url);

        $statusCode = 200;
        $response = $manuais;
		return Response::json($response, $statusCode);
	}
}
