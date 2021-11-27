<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 25/09/2017
 * Time: 18:06
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 25/09/2017
 * Time: 18:06
 */

namespace App\Http\Requests\Faq;


use App\Http\Request\Request;
use Illuminate\Support\Facades\Validator;

class FaqRequest extends Request{
	public function rules(){
		return [
			'pergunta' => 'required|min:4|max:255',
			'resposta' => 'required|min:4',
		];
	}

	public function messages(){
		return
			[
				'pergunta.required' => 'Pergunta obrigatória',
				'pergunta.max' => 'A pergunta não pode passar de 255 caracteres',
				'pergunta.min' => 'A pergunta deve ter no mínimo 3 caracteres',
				'resposta.required' => 'Resposta obrigatória',
				'resposta.min' => 'A resposta deve ter no mínimo 3 caracteres',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}
}