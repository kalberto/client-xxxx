<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/10/2020
 * Time: 11:20
 */

namespace App\Http\Traits;

use App\LogAdministrador;
use Illuminate\Support\Facades\Auth;

trait ModelLogTrait {

	protected function getPrimaryKeyForLog(){
		$primary_key = "";
		$name = $this->getKeyName();
		if(is_array($name)){
			foreach($name as $value){
				$primary_key .= $value.":".$this->$value.";";
			}
		}else{
			$primary_key = $this->$name;
		}
		return $primary_key;
	}

	protected function saveLog($ip,$tipo,$dados = null){
		if(Auth::id() !== null){
			$data_log = array(
				'administrador_id' => Auth::id(),
				'registro_id' => $this->getPrimaryKeyForLog(),
				'tabela' => $this->getTable(),
				'tipo' => $tipo,
				'ip' => $ip == false ? '127.0.0.1' : $ip,
				'alteracoes' => isset($dados) ? $dados : ''
			);
			LogAdministrador::store($data_log);
		}
	}

	protected function deleteLog($ip){
		if(Auth::id() !== null){
			$data  = array (
				'administrador_id' => Auth::id(),
				'registro_id' => $this->getPrimaryKeyForLog(),
				'tabela' => $this->getTable(),
				'tipo' => 'delete',
				'ip' => $ip == false ? '127.0.0.1' : $ip,
				'alteracoes' => ''
			);
			LogAdministrador::store($data);
		}
	}
}