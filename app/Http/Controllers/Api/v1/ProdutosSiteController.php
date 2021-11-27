<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/10/2020
 * Time: 15:23
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\ProdutosSite\CreateRequest;
use App\Http\Requests\ProdutosSite\EditRequest;
use App\Models\Site\Produtos;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProdutosSiteController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:7' );
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
		    $data = Produtos::where(['ativo' => $state]);
	    else
		    $data = Produtos::where('produtos_site.id','!=',null);
	    if(isset($params['q'])){
		    $q = $params['q'];
		    $data->join('produtos_site_translation','produtos_site.id','=','produtos_site_translation.owner_id');
		    $data->whereRaw("UPPER(produtos_site_translation.title) like UPPER('%$q%')")
		         ->orWhereRaw("UPPER(produtos_site_translation.sub_title) like UPPER('%$q%')");
	    }
	    $ids = $data->groupBy('produtos_site.id')->pluck('produtos_site.id');
	    $data = DB::table('produtos_site')->select(['produtos_site.id','produtos_site_translation.title','produtos_site_translation.sub_title'])
	              ->join('produtos_site_translation',function(JoinClause $join){
		              $join->on('produtos_site.id','=','produtos_site_translation.owner_id')
		                   ->join('languages','produtos_site_translation.language_id','=','languages.id')
		                   ->where('languages.default','=',1);
	              })->whereIn('produtos_site.id',$ids);
	    if(Schema::hasColumn('produtos_site',$order_by)){
		    if($asc)
			    $data = $data->orderBy('produtos_site.'.$order_by)->paginate($limit);
		    else
			    $data = $data->orderByDesc('produtos_site.'.$order_by)->paginate($limit);
	    }else{
		    if (Schema::hasColumn('produtos_site_translation', $order_by)){
			    if($asc)
				    $data = $data->orderBy('produtos_site_translation.'.$order_by)->paginate($limit);
			    else
				    $data = $data->orderByDesc('produtos_site_translation.'.$order_by)->paginate($limit);
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
			    'msg' => 'Nenhum produto encontrado!'
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
	    $registro = Produtos::store($data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Produto cadastrado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao salvar o Produto'
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
			'msg' => 'Não foi possível encontrar esse produto'
		];
		if(isset($id) && is_numeric($id)){
			$registro = Produtos::find($id);
			if(isset($registro)){
				$registro->append(['languages','servicos','videos']);
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
	    $registro = Produtos::edit($id,$data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Produto editado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao editar o Produto'
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
        if(isset($id) && is_numeric($id) && ($registro = Produtos::find($id)) != null){
        	if($registro->remove($request->ip())){
		        $statusCode = 200;
		        $response = [
			        'msg' => 'Produto deletado com sucesso!'
		        ];
	        }else{
		        $statusCode = 500;
		        $response = [
			        'msg' => 'Erro ao deletar o produto!'
		        ];
	        }
        }else{
	        $statusCode = 404;
	        $response = [
		        'msg' => 'Não foi possível encontrar esse produto.'
	        ];
        }
	    return response()->json($response, $statusCode);
    }
}
