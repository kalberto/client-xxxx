<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 22/03/2018
 * Time: 10:06
 *
 * Last edited by Alberto de Almeida Guilherme
 */

namespace App\Http\Request\Organizacao;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;


class CreateRequest extends Request {


	public function rules(){
		return [
			'nome' => 'required|min:3|max:150'
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de 150 caracteres',
				'nome.min' => 'O Nome deve ter no mínimo 3 caracteres'
			];
	}


	public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}