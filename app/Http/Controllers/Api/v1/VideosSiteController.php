<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/10/2020
 * Time: 15:30
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\VideosSite\CreateRequest;
use App\Http\Requests\VideosSite\EditRequest;
use App\Models\Site\Videos;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VideosSiteController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct() {
		$this->middleware( 'role:9' );
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
	        $data = Videos::where(['ativo' => $state]);
	    else
	    	$data = Videos::where('videos_site.id','!=',null);
	    if(isset($params['q'])){
		    $q = $params['q'];
		    $data->join('videos_site_translation','videos_site.id','=','videos_site_translation.owner_id');
		    $data->whereRaw("UPPER(videos_site_translation.title) like UPPER('%$q%')");
	    }
	    $ids = $data->groupBy('videos_site.id')->pluck('videos_site.id');
	    $data = DB::table('videos_site')->select(['videos_site.id','videos_site_translation.title'])
	              ->join('videos_site_translation',function(JoinClause $join){
		              $join->on('videos_site.id','=','videos_site_translation.owner_id')
		                   ->join('languages','videos_site_translation.language_id','=','languages.id')
		                   ->where('languages.default','=',1);
	              })->whereIn('videos_site.id',$ids);
	    if(Schema::hasColumn('videos_site',$order_by)){
		    if($asc)
			    $data = $data->orderBy('videos_site.'.$order_by)->paginate($limit);
		    else
			    $data = $data->orderByDesc('videos_site.'.$order_by)->paginate($limit);
	    }else{
		    if (Schema::hasColumn('videos_site_translation', $order_by)){
			    if($asc)
				    $data = $data->orderBy('videos_site_translation.'.$order_by)->paginate($limit);
			    else
				    $data = $data->orderByDesc('videos_site_translation.'.$order_by)->paginate($limit);
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
			    'msg' => 'Nenhum vídeo encontrado!'
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
	    $registro = Videos::store($data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Vídeo cadastrado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao salvar o vídeo'
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
			'msg' => 'Não foi possível encontrar esse vídeo'
		];
		if(isset($id) && is_numeric($id)){
			$registro = Videos::find($id);
			if(isset($registro)){
				$registro->append(['languages','video','thumb']);
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
	    $registro = Videos::edit($id,$data,$request->ip());
	    if($registro != false){
		    $statusCode = 200;
		    $response = [
			    'msg' => 'Vídeo editado com sucesso!'
		    ];
	    }else{
		    $statusCode = 500;
		    $response = [
			    'msg' => 'Ocorreu um erro ao editar o vídeo'
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
        if(isset($id) && is_numeric($id) && ($registro = Videos::find($id)) != null){
        	if($registro->remove($request->ip())){
		        $statusCode = 200;
		        $response = [
			        'msg' => 'Vídeo deletado com sucesso!'
		        ];
	        }else{
		        $statusCode = 500;
		        $response = [
			        'msg' => 'Erro ao deletar o vídeo!'
		        ];
	        }
        }else{
	        $statusCode = 404;
	        $response = [
		        'msg' => 'Não foi possível encontrar esse vídeo.'
	        ];
        }
	    return response()->json($response, $statusCode);
    }

    public function getVideosByLocale($locale){
    	$language = DB::table('languages')->where('locale','=',$locale)->first();
    	if(isset($language)){
		    $videos = DB::table('videos_site')->select(['videos_site.id','videos_site_translation.title'])
		      ->join('videos_site_translation',function(JoinClause $join) use($language){
			      $join->on('videos_site.id','=','videos_site_translation.owner_id')
			           ->where('language_id','=',$language->id);
		      })->get();
		    $statusCode = 200;
		    $response = [
		    	'registros' => $videos
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
