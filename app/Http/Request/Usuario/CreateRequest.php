<?php
/**
 * Created by Alberto de Almeida Guilherme.
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/11/2017
 * Time: 11:25
 */

namespace App\Http\Request\Usuario;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;


class CreateRequest extends Request {


	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'min:3|max:255',
			'email' => 'required|email|max:100',
			'login' => 'required|max:100|unique:usuario,login,NULL,id,deleted_at,NULL',
			'password' => 'required|min:6|max:12',
			'api_token' => 'required|unique:usuario|size:60',
			're_password' => 'required|same:password',
			'slug' => 'required'
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de :max caracteres',
				'nome.min' => 'O Nome deve ter no mínimo :min caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de :max caracteres',
				'sobrenome.min' => 'O Sobrenome deve ter no mínimo :min caracteres',
				'email.required' => 'E-mail obrigatório',
				'email.email' => 'E-mail no formato inválido',
				'email.max' => 'O e-mail deve conter no máximo :max caracteres',
				'login.required' => 'Login obrigatório',
				'login.unique' => 'Este login já está cadastrado',
				'login.max' => 'O login deve conter no máximo :max caracteres',
				'password.required' => 'Senha obrigatória',
				'password.max' => 'A Senha deve possuir no máximo :max caracteres',
				'password.min' => 'A Senha deve possuir no mínimo :min caracteres',
				're_password.required' => 'Confirme sua senha',
				're_password.same' => 'As senhas devem ser iguais',
				'slug.required' => 'Não é possível cadastrar um usuário sem uma organização',
			];
	}


	public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}