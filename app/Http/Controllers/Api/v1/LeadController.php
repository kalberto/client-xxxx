<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 03/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Lead;
use App\LogAdministrador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class LeadController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'role:5' );
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
        $data = Lead::whereNotNull('id');

        if(isset($params['state']) ){
            $state = boolval($params['state']);
            $data = $data->where(['ativo' => $state]);
        }
        if(isset($params['q'])){
            $q = $params['q'];
            $data = $data->where('empresa','like','%'.$q.'%')
                ->orWhere('contato','like','%'.$q.'%')
                ->orWhere('assunto','like','%'.$q.'%');
        }
        if (Schema::hasColumn('lead', $order_by)){
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
                'msg' => 'Nenhum lead encontrado!'
            ];
        }
	    Return Response::json($response, $statusCode);
    }

     public function export(Request $request){
	    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	    header ("Cache-Control: no-cache, must-revalidate");
	    header ("Pragma: no-cache");
	    header ("Content-type: application/x-msexcel");
	    header ("Content-Disposition: attachment; filename=\"leads.xls\"" );
	    header ("Content-Description: PHP Generated Data" );


	    $lead = new Lead();
	    $leads = $lead->orderBy('id', 'DESC')->get();

	    echo utf8_decode('
        <table>
        <tr>
            <th>Empresa</th>
            <th>E-mail</th>
            <th>Contato</th>
            <th>Assunto</th>
            <th>Mensagem</th>
            <th>Telefone</th>
            <th>Cep</th>
            <th>Origem</th>
            <th>Data</th>
        </tr>');

	    foreach ($leads as $lead) {
		    echo '<tr><td>'.utf8_decode($lead->empresa)
		         .'</td><td>'.utf8_decode($lead->email)
		         .'</td><td>'.utf8_decode($lead->contato)
		         .'</td><td>'.utf8_decode($lead->assunto)
		         .'</td><td>'.utf8_decode($lead->mensagem)
		         .'</td><td>'.utf8_decode($lead->telefone)
		         .'</td><td>'.utf8_decode($lead->cep)
		         .'</td><td>'.utf8_decode($lead->origem)
		         .'</td><td>'.$lead->created_at.
		         '</td></tr>';
	    }

	    echo '</table>';
    }

	/**
	 * Remove the specified resource from storage.
	 * * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Lead-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id)
	{
		if($id != null && is_numeric($id) && ($lead = Lead::find($id)) != null){
			if($lead->delete()){
				$data = array(
					'administrador_id' => Auth::user()->id,
					'registro_id' => $id,
					'tabela' => 'lead',
					'tipo' => 'delete',
					'ip' => $request->ip()
				);
				LogAdministrador::store($data);
				$statusCode = 200;
				$response = [
					'msg' => 'Lead deletado com sucesso!'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o lead!'
				];
			}
		}
		else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse lead.'
			];
		}
		return Response::json($response, $statusCode);
	}
}
