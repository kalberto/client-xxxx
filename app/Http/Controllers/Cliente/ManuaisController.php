<?php
/**
 * Created by Yeshua Emanuel Braz
 * Date: 17/01/2018
 * Time: 14:21
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/02/2017
 * Time: 15:12
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use App\Manual;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ManuaisController extends AreaClienteController{

	public function __construct() {
		parent::__construct();
		$this->middleware(array('role:2','cliente_api'));
		$login = env('API_HTT_LOGIN');
		$pass = env('API_HTT_PASS');
		$this->ossapi = new OSSAPI(env('API_HTT_URL'),env('API_HTT_PROXY'),$login,$pass);
		$this->auth = $this->ossapi->callAPI('auth',array('username' =>$login, 'password' => $pass),null);
		$this->data['menu'] = '/cliente/chamados';
	}

	/**
	 * @param  \Illuminate\Http\Request $request
	 * @param  string $url	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(Request $request, $url){
		$this->beforeReturn();
		$nome = 'Not Found';
        if(!$this->authenticate()){
            $databaseName = [];
        }else{
            $produtos_api = $this->ossapi->callAPI('services.get_tipoServico_for_product',array(),$this->auth->result->auth);
            if(isset($produtos_api->result)){
                $exist = array();
                $produtos_api = $produtos_api->result;
                foreach ($produtos_api as $key => $produto_api){
                    foreach ($produto_api as $produto){
                        if(str_slug($produto) == $url){
                            $nome = $produto;
                            if(!isset($databaseUrl[$key])){
                                $exist[$key] = true;
                            }
                        }
                    }
                }
                $databaseName = array_keys($exist);
            }else{
                $databaseName = [];
            }
        }
        $manuaisDB = Manual::getByServicosName($databaseName)->get();
        $manuais = [];
        if($manuaisDB->count() > 0){
            foreach ($manuaisDB as $manual){
                $manuais[] = [
                    'nome' => $manual->nome,
                    'url' => url($manual->media->media_root->path.$manual->media->nome)];
            }
        }else{
            $manuais = [];
        }
        $this->data['manuais'] = $manuais;
        $this->data['nome'] = $nome;
		return view('site.clientes.manuais.manuais',$this->data);
	}
}