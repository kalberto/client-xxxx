<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/10/2020
 * Time: 11:50
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\ServicosSite\CreateRequest;
use App\Http\Requests\ServicosSite\EditRequest;
use App\Models\Site\Servicos;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServicosSiteController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct() {
		$this->middleware( 'role:8' );
	}
    /**
     * Display a listing of the resource.
     * RF0035
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $params = $request->all();
	    $order_by = 'id';
	    $asc = true;
	    $fields = '';
	    $state = null;
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
	    if(isset($state))
	        $data = Servicos::where(['ativo' => $state]);
	    else
	    	$data = Servicos::where('servicos_site.id','!=',null);
	    if(isset($params['q'])){
		    $q = $params['q'];
		    $data->join('servicos_site_translation','servicos_site.id','=','servicos_site_translation.owner_id');
		    $data->whereRaw("UPPER(servicos_site_translation.title) like UPPER('%$q%')")
		         ->orWhereRaw("UPPER(servicos_site_translation.sub_title) like UPPER('%$q%')");
	    }
	    $ids = $data->groupBy('servicos_site.id')->pluck('servicos_site.id');
	    $data = DB::table('servicos_site')->select(['servicos_site.id','servicos_site_translation.title','servicos_site_translation.sub_title'])
	              ->join('servicos_site_translation',function(JoinClause $join){
		              $join->on('servicos_site.id','=','servicos_site_translation.owner_id')
		                   ->join('languages','servicos_site_translation.language_id','=','languages.id')
		                   ->where('languages.default','=',1);
	              })->whereIn('servicos_site.id',$ids);
	    if(Schema::hasColumn('servicos_site',$order_by)){
		    if($asc)
			    $data = $data->orderBy('servicos_site.'.$order_by)->paginate($limit);
		    else
			    $data = $data->orderByDesc('servicos_site.'.$order_by)->paginate($limit);
	    }else{
		    if (Schema::hasColumn('servicos_site_translation', $order_by)){
			    if($asc)
				    $data = $data->orderBy('servicos_site_translation.'.$order_by)->paginate($limit);
			    else
				    $data = $data->orderByDesc('servicos_site_translation.'.$order_by)->paginate($limit);
		    }
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
			    'msg' => 'Nenhum serviço encontrado!'
		    ];
	    }
	    Return response()->json($response, $statusCode);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function store(CreateRequest $request)
    {
	    $data = $request->all();
	    $registro = Servicos::store($data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Serviço cadastrado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao salvar o serviço'
		    ];
	    }
	    return response()->json($response,$statusCode);
    }

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id = null)
	{
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar esse serviço'
		];
		if(isset($id) && is_numeric($id)){
			$registro = Servicos::find($id);
			if(isset($registro)){
				$registro->append(['languages','videos','file','servicos']);
				$statusCode = 200;
				$response = $registro;
			}
		}
		return response()->json($response,$statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function update(EditRequest $request, $id = null)
    {
	    $data = $request->all();
	    $registro = Servicos::edit($id,$data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Serviço editado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao editar o serviço'
		    ];
	    }
	    return response()->json($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     * RF0038
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
        if(isset($id) && is_numeric($id) && ($registro = Servicos::find($id)) != null){
        	if($registro->remove($request->ip())){
		        $statusCode = 200;
		        $response = [
			        'msg' => 'Serviço deletado com sucesso!'
		        ];
	        }else{
		        $statusCode = 500;
		        $response = [
			        'msg' => 'Erro ao deletar o serviço!'
		        ];
	        }
        }else{
	        $statusCode = 404;
	        $response = [
		        'msg' => 'Não foi possível encontrar esse serviço.'
	        ];
        }
	    return response()->json($response, $statusCode);
    }

    public function getServicosByLocale($locale){
    	$language = DB::table('languages')->where('locale','=',$locale)->first();
    	if(isset($language)){
		    $servicos = DB::table('servicos_site')->select(['servicos_site.id','servicos_site_translation.title'])
		      ->join('servicos_site_translation',function(JoinClause $join) use($language){
			      $join->on('servicos_site.id','=','servicos_site_translation.owner_id')
			           ->where('language_id','=',$language->id);
		      })->get();
		    $statusCode = 200;
		    $response = [
		    	'registros' => $servicos
		    ];
	    }else{
    		$statusCode = 404;
    		$response = [
    			'registros' => [],
			    'msg' => 'Linguagem inválida'
		    ];
	    }
	    return response()->json($response,$statusCode);
    }
}
