<?php
/**
 * Created by Alberto de Almeida Guilherme.
 */

namespace App\Http\Request\Administrador;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;


class CreateRequest extends Request {


	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'min:3|max:255',
			'email' => 'required|email|max:255|unique:administrador,email,NULL,id,deleted_at,NULL',
			'password' => 'required|min:6|max:12',
			'api_token' => 'required|unique:administrador|size:60',
			're_password' => 'required|same:password'
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de 255 caracteres',
				'nome.min' => 'O Nome deve ter no mínimo 3 caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de 255 caracteres',
				'sobrenome.min' => 'O Sobrenome deve ter no mínimo 3 caracteres',
				'email.required' => 'E-mail obrigatório',
				'email.email' => 'E-mail no formato inválido',
				'email.unique' => 'Este e-mail já está cadastrado',
				'password.required' => 'Senha obrigatória',
				'password.max' => 'A Senha deve possuir no máximo 12 caracteres',
				'password.min' => 'A Senha deve possuir no mínimo 6 caracteres',
				're_password.required' => 'Confirme sua senha',
				're_password.same' => 'As senhas devem ser iguais',
			];
	}


	public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}