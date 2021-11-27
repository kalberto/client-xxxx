<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/10/2020
 * Time: 16:21
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Paginas\CreateRequest;
use App\Http\Requests\Paginas\EditRequest;
use App\Models\Site\Paginas;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaginasController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:10');
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
	    $data = Paginas::where('paginas.id','!=',null);
	    if(isset($params['q'])){
		    $q = $params['q'];
		    $data->join('paginas_translation','paginas.id','=','paginas_translation.owner_id');
		    $data->whereRaw("UPPER(paginas_translation.title) like UPPER('%$q%')")
		         ->orWhereRaw("UPPER(paginas_translation.sub_title) like UPPER('%$q%')");
	    }
	    $ids = $data->groupBy('paginas.id')->pluck('paginas.id');
	    $data = DB::table('paginas')->select(['paginas.id','paginas_translation.title','paginas_translation.sub_title','paginas_translation.url'])
	              ->join('paginas_translation',function(JoinClause $join){
		              $join->on('paginas.id','=','paginas_translation.owner_id')
		                   ->join('languages','paginas_translation.language_id','=','languages.id')
		                   ->where('languages.default','=',1);
	              })->whereIn('paginas.id',$ids);
	    if(Schema::hasColumn('paginas',$order_by)){
		    if($asc)
			    $data = $data->orderBy('paginas.'.$order_by)->paginate($limit);
		    else
			    $data = $data->orderByDesc('paginas.'.$order_by)->paginate($limit);
	    }else{
		    if (Schema::hasColumn('paginas_translation', $order_by)){
			    if($asc)
				    $data = $data->orderBy('paginas_translation.'.$order_by)->paginate($limit);
			    else
				    $data = $data->orderByDesc('paginas_translation.'.$order_by)->paginate($limit);
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
			    'msg' => 'Nenhuma página encontrado!'
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
	    $registro = Paginas::store($data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Página cadastrada com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao salvar a página'
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
			'msg' => 'Não foi possível encontrar essa página'
		];
		if(isset($id) && is_numeric($id)){
			$registro = Paginas::find($id);
			if(isset($registro)){
				$registro->append(['languages','videos']);
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
	    $registro = Paginas::edit($id,$data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Página editada com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao editar a página'
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
        if(isset($id) && is_numeric($id) && ($registro = Paginas::find($id)) != null){
        	if($registro->remove($request->ip())){
		        $statusCode = 200;
		        $response = [
			        'msg' => 'Página deletado com sucesso!'
		        ];
	        }else{
		        $statusCode = 500;
		        $response = [
			        'msg' => 'Erro ao deletar o página!'
		        ];
	        }
        }else{
	        $statusCode = 404;
	        $response = [
		        'msg' => 'Não foi possível encontrar esse página.'
	        ];
        }
	    return response()->json($response, $statusCode);
    }
}
