<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/10/2017
 * Time: 18:20
 */

namespace App\Http\Controllers\Api\v1;

use App\Faq;
use App\Http\Requests\Faq\FaqRequest;
use App\LogAdministrador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;

class FaqController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:4' );
	}
    /**
     * Display a listing of the resource.
     * RF0035
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    //state - ativo ou não
	    //fields - quais fields é para trazer
	    //sort - order by
	    //limit - quantos registros
	    //q - busca na tabela
	    $params = $request->all();
        $order_by = 'id';
        $asc = true;
        $fields = '';
        $state = true;
        $q = null;
        $limit = 13;
        $sort = null;
        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $limit = $params['limit'];
        }
        if(isset($params['sort'])){
            $order_by = $params['sort'];
            if(substr($order_by,0,1) == '-'){
                $asc = false;
                $order_by = substr($order_by,1);
            }
        }
        if(isset($params['fields'])){
            $fields = $params['fields'];
        }
        if(isset($params['state']) ){
            $state = boolval($params['state']);
        }
        $data = Faq::where(['ativo' => $state]);
        if(isset($params['q'])){
            $q = $params['q'];
            $data = $data->where('pergunta','like','%'.$q.'%')
                ->orWhere('resposta','like','%'.$q.'%');
        }
        if (Schema::hasColumn('faq', $order_by)){
            if($asc)
                $data = $data->orderBy($order_by)->paginate($limit);
            else
                $data = $data->orderByDesc($order_by)->paginate($limit);
        }
        if($fields != ''){
            $fields = explode(',',$fields);
            $data->transform(function ($item,$key) use ($fields) {
                return collect($item)->only($fields);
            });
        }
        if($data->total() > 0){
            $statusCode = 200;
            $response = $data->appends($params);
        }
        else{
            $statusCode = 200;
            $response = [
                'msg' => 'Nenhuma Faq encontrada!'
            ];
        }
	    Return Response::json($response, $statusCode);
    }

    /**
     * Show the form for creating a new resource.
     * RF0036
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO RF0036
    }

    /**
     * Store a newly created resource in storage.
     * RF0036
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $data = $request->all();
        $faqRequest = new FaqRequest();
        $validate = $faqRequest->validar($data);
        if(!$validate->fails()){
            if(Faq::store($data,$request->ip())){
                $statusCode = 201;
                $response = [
                    'msg' => 'Faq cadastrada com sucesso!'
                ];
            }else{
                $statusCode = 500;
                $response = [
                    'msg' => 'Ocorreu um erro ao salvar a Faq'
                ];
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao salvar a Faq',
                'error_validate' => $validate->errors()->all()
            ];
        }
	    return Response::json($response, $statusCode);
    }

	/**
	 * Display the specified resource.
	 * @param  \App\Categoria-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id = null)
	{
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar essa faq'
        ];
        if(isset($id) && is_numeric($id)){
            $faq = Faq::find($id);
            if(isset($faq)){
                $statusCode = 200;
                $response = $faq;
            }
        }
		return Response::json($response,$statusCode);
	}

    /**
     * Show the form for editing the specified resource.
     * RF0037
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        //TODO RF0037
    }

    /**
     * Update the specified resource in storage.
     * RF0037
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        if(isset($id) && is_numeric($id) && Faq::find($id) != null){
            $faqRequest = new FaqRequest();
            $data = $request->all();
            $validate = $faqRequest->validar($data);
            if(!$validate->fails()){
                if(Faq::edit($id, $data,$request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Faq editado com sucesso!'
                    ];
                }else{
                    $statusCode = 500;
                    $response = [
                        'msg' => 'Ocorreu um erro ao editar o Faq'
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o Faq',
                    'error_validate' => $validate->errors()->all()
                ];
            }
        }else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse Faq'
            ];
        }
	    return Response::json($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     * RF0038
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
        if(isset($id) && is_numeric($id) && ($faq = Faq::find($id)) != null){
	        $data = array(
		        'administrador_id' => Auth::user()->id,
		        'registro_id' => $id,
		        'tabela' => 'faq',
		        'tipo' => 'delete',
		        'ip' => $request->ip()
	        );
	        LogAdministrador::store($data);
	        if($faq->delete()){
		        $statusCode = 200;
		        $response = [
			        'msg' => 'Faq deletado com sucesso!'
		        ];
	        }else{
		        $statusCode = 500;
		        $response = [
			        'msg' => 'Erro ao deletar o Faq!'
		        ];
	        }
        }else{
	        $statusCode = 404;
	        $response = [
		        'msg' => 'Não foi possível encontrar esse Faq.'
	        ];
        }
	    return Response::json($response, $statusCode);
    }

	/**
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function search(Request $request){
        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0)
            $limit = $params['limit'];
        else
            $limit = 20;

        if(isset($params['search'])){
            $faqs = Faq::search($params['search'])->paginate($limit);
        }else {
            $faqs = Faq::paginate( $limit );
        }
        $statusCode = 200;
        $response = $faqs;
        return Response::json($response, $statusCode);
	}

	/**
	 * Remove the specified resource from storage.
	 * * @param  \Illuminate\Http\Request  $request
	 * @param  string $url
	 * @return \Illuminate\Http\Response
	 */
	public function getFaqsServico(Request $request, $url){
        $faqs = Faq::getByServico($url);
        $statusCode = 200;
        $response = $faqs;
		return Response::json($response, $statusCode);
	}
}
