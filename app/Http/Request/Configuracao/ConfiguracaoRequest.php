<?php
/**
 * Created by Alberto de Almeida Guilherme.
 */

namespace App\Http\Requests\Configuracao;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class ConfiguracaoRequest extends Request {

	public function rules(){
		return [
			'nome_app' => 'required|min:3|max:150',
		];
	}

	public function messages(){
		return
			[
				'nome_app.required' => 'O nome é obrigatório',
				'nome_app.max' => 'O nome não pode passar de 150 caracteres',
				'nome_app.min' => 'O nome deve ter no mínimo 3 caracteres',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}