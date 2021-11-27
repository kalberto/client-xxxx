<?php
/**
 * User: alberto.almeida
 * Date: 04/08/2017
 * Time: 17:25
 */

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SendMailRequest extends FormRequest
{

	public function rules()
	{
		return
			[
				'nome'  => 'required | max:50 | min: 4',
				//'empresa' => 'required | max:50 | min: 4',
				'email' => 'required | email',
				//'telefone' => 'required | max:20 | min: 8',
				'assunto' => 'required',
				//'mensagem' => 'required | max:700 | min: 10'
			];
	}

	public function messages()
	{
		return
			[
				/*'nome.required' => 'O campo contato é obrigatório',
				'nome.max' => 'O máximo é de 50 caracteres',
				'nome.min' => 'O mínimo é de 4 caracteres',
				'empresa.required' => 'O campo empresa é obrigatório',
				'empresa.max' => 'O máximo é de 50 caracteres',
				'empresa.min' => 'O mínimo é de 4 caracteres',
				'email.required' => 'O campo e-mail é obrigatório',
				'email.email' => 'Insira um e-mail válido',
				//'telefone.required' => 'O campo telefone é obrigatório',
				//'telefone.max' => 'O máximo é de 20 caracteres',
				//'telefone.min' => 'O mínimo é de 8 caracteres',
				//'assunto.required' => 'O campo assunto é obrigatório',
				//'mensagem.required' => 'O campo mensagem é obrigatório',
				//'mensagem.max' => 'O máximo é de 750 caracteres',
				//'mensagem.min' => 'O mínimo é de 10 caracteres'*/
			];
	}

	public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}