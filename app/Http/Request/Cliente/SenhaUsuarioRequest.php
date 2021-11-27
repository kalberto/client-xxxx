<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 29/01/2018
 * Time: 14:56
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 29/01/2018
 * Time: 14:56
 */

namespace App\Http\Request\Cliente;

use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class SenhaUsuarioRequest extends Request{

	public function rules(){
		return [
			'password' => 'required',
			'new_password' => 'required|min:6|max:25|regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[@!*$#%.]).*$/i',
			'new_password_confirmation' => 'required|same:new_password'
		];
	}

	public function messages(){
		return
			[
				'password.required' => 'Senha atual obrigatória',
				'new_password.required' => 'Nova senha obrigatória',
				'new_password.min' => 'A nova senha deve possuir no mínimo 6 caracteres',
				'new_password.max' => 'A nova senha deve possuir no máximo :max caracteres',
				'new_password.regex' => 'Deve conter: Maiúscula, minúscula, número e caracter especial(@!*$#%.)',
				'new_password_confirmation.required' => 'Confirmação obrigatória',
				'new_password_confirmation.same' => 'Deve ser igual a senha',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}