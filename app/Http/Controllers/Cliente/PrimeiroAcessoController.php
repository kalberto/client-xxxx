<?php
/**
 * Created by Yeshua Emanuel Braz
 * Date: 17/01/2018
 * Time: 14:21
 *
 * Last edited by Yeshua Emanuel Braz
 * Date: 17/01/2018
 * Time: 15:56
 */

namespace App\Http\Controllers\Cliente;

use App\Helpers\OSSAPI;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class PrimeiroAcessoController extends AreaClienteController{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->beforeReturn();
		return view('site.clientes.primeiro-acesso',$this->data);
	}
}