<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 09/11/2017
 * Time: 09:33
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 13/11/2017
 * Time: *
 */

namespace App\Http\Request\Manual;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class ManualRequest extends Request{
	public function rules(){
		return [
			'nome' => 'required|min:5|max:255',
			'media_id' => 'required',
		];
	}

	public function messages(){
		return
			[
				'nome.required' => 'O nome é obrigatório',
				'nome.max' => 'O nome não pode passar de 255 caracteres',
				'nome.min' => 'O nome deve ter no mínimo 5 caracteres',
				'media_id.required' => 'Pdf obrigatório',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}
}