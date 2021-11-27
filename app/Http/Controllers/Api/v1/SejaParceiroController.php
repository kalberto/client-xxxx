<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 22/03/2021
 * Time: 18:20
 */

namespace App\Http\Controllers\Api\v1;

use App\Models\Site\SejaParceiro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class SejaParceiroController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:11' );
	}
    /**
     * Display a listing of the resource.
     * RF0025
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
	    $order_by = 'created_at';
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
	    $data = SejaParceiro::whereNotNull('id');
	    if (Schema::hasColumn('seja_parceiro', $order_by)){
		    if($asc)
			    $data = $data->orderBy($order_by)->paginate($limit);
		    else
			    $data = $data->orderByDesc($order_by)->paginate($limit);
	    }else{
		    $data = $data->orderBy('created_at')->paginate($limit);
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
			    'msg' => 'Nenhum registro encontrado!'
		    ];
	    }
	    return response()->json($response, $statusCode);
    }

     public function export(Request $request){
	    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	    header ("Cache-Control: no-cache, must-revalidate");
	    header ("Pragma: no-cache");
	    header ("Content-type: application/x-msexcel");
	    header ("Content-Disposition: attachment; filename=\"seja_um_parceiro_leads.xls\"" );
	    header ("Content-Description: PHP Generated Data" );

	    $registros = SejaParceiro::orderBy('id', 'DESC')->get();

	    echo utf8_decode('
        <table>
        <tr>
            <th>Documento</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Número</th>
            <th>Razão social</th>
            <th>Endereço</th>
            <th>Cidade</th>
            <th>Ramo atuação</th>
            <th>Redes Sociais</th>
            <th>Data</th>
        </tr>');

	    foreach ($registros as $registro) {
	    	$redes_sociais = '';
	    	if(isset($registro->data->redes_sociais)){
			    foreach ($registro->data->redes_sociais as $item){
				    if($redes_sociais !== '')
					    $redes_sociais .= ', ';
				    $redes_sociais .= $item;
			    }
		    }

		    echo '<tr><td>'.utf8_decode($registro->documento)
		         .'</td><td>'.(isset($registro->data->nome) ? utf8_decode($registro->data->nome) : '')
		         .'</td><td>'.(isset($registro->data->email) ? utf8_decode($registro->data->email) : '')
		         .'</td><td>'.(isset($registro->data->telefone) ? utf8_decode($registro->data->telefone) : '')
		         .'</td><td>'.(isset($registro->data->razao_social) ? utf8_decode($registro->data->razao_social) : '')
		         .'</td><td>'.(isset($registro->data->endereco) ? utf8_decode($registro->data->endereco) : '')
		         .'</td><td>'.((isset($registro->data->cidade) && isset($registro->data->uf)) ? (utf8_decode($registro->data->cidade).'-'.utf8_decode($registro->data->uf)) : '')
		         .'</td><td>'.(isset($registro->data->ramo_atuacao) ? utf8_decode($registro->data->ramo_atuacao) : '')
		         .'</td><td>'.utf8_decode($redes_sociais)
		         .'</td><td>'.$registro->created_at
		         .'</td></tr>';
	    }

	    echo '</table>';
    }

	/**
	 * Remove the specified resource from storage.
	 * * @param  \Illuminate\Http\Request  $request
	 * @param  \App\SejaParceiro-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id)
	{
		if(isset($id) && is_numeric($id) && ($registro = SejaParceiro::find($id)) != null){
			if($registro->remove($request->ip())){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro deletado com sucesso!'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o registro!'
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse registro.'
			];
		}
		return response()->json($response, $statusCode);
	}
}
