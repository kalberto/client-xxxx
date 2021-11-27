<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 15:58
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 09/02/2018
 * Time: 17:00
 */

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Support\Facades\Auth;

class AreaClienteController extends Controller {
	protected $data = [];

	public function __construct() {
	}

	public function beforeReturn(){
		$usuario =  Auth::guard('cliente_api')->user();
		$usuario->load('media.media_root');
		$last_login = $usuario->acessos()->latest('id')->skip(1)->first();
		$menu = $usuario->modulos()->where('menu',true)->orderBy('order')->get()->toArray();
		$this->data['usuario'] = [
			'nome' => $usuario->nome,
			'ultimo_acesso' => isset($last_login) ? date('d/m/Y',strtotime($last_login->data)) : isset($usuario->ultimo_acesso) ? date('d/m/Y',strtotime($usuario->ultimo_acesso)) : false,
			'menu' => $menu,
			'media' => $usuario->media
		];
	}
}