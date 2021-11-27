<?php
/**
 * Created by Alberto de Almeida Guilherme.
 */

namespace App\Http\Request\Usuario;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class UpdateRequest extends Request {

	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'max:255',
			'password' => 'min:6|max:12',
			're_password' => 'same:password'
		];
	}

	public function messages(){
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de :max caracteres',
				'nome.min' => 'O Sobrenome deve ter no mínimo :min caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de :max caracteres',
				'password.max' => 'A Senha deve possuir no máximo :max caracteres',
				'password.min' => 'A Senha deve possuir no mínimo :min caracteres',
				're_password.same' => 'As senhas devem ser iguais',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}