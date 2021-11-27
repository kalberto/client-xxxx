<?php
/**
 * Created by Alberto de Almeida Guilherme.
 */

namespace App\Http\Request\Usuario;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class PassRequest extends Request {

	public function rules(){
		return [
			'password' => 'required|min:6|max:12',
			're_password' => 'required|same:password'
		];
	}

	public function messages(){
		return
			[
				'password.required' => 'Senha obrigatória',
				'password.max' => 'A Senha deve possuir no máximo :max caracteres',
				'password.min' => 'A Senha deve possuir no mínimo :min caracteres',
				're_password.required' => 'Confirmação obrigatória',
				're_password.same' => 'As senhas devem ser iguais',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}