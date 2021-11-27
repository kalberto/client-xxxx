<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 29/01/2018
 * Time: 14:55
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 29/01/2018
 * Time: 14:55
 */

namespace App\Http\Request\Cliente;

use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class EditUsuarioRequest extends Request{

	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'max:255',
			'cep' => 'required|numeric'
		];
	}

	public function messages(){
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de 255 caracteres',
				'nome.min' => 'O Sobrenome deve ter no mínimo 3 caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de 255 caracteres',
				'cep.required' => 'Cep obrigatório',
				'cep.size' => 'O Cep deve conter :size números',
				'cep.numeric' => 'O cep deve ser composto de números'

			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}