<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 22/03/2021
 * Time: 16:41
 */

namespace App\Http\Requests\Web\Contato;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Web\Request as BaseRequest;
use App\Rules\DocumentoValid;

class SejaParceiroRequest extends BaseRequest{
	public function rules() {
		return [
			'documento' => ['required', 'digits:14', new DocumentoValid()],
			'data.nome' => 'required|max:50',
			'data.email' =>'required|max:50|email|regex:/^\S*$/u',
			'data.telefone' =>'required|digits_between:10,11',
			'data.razao_social' => 'required|max:70',
			'data.endereco' => 'required|max:150',
			'data.uf' =>'required|exists:estados,uf',
			'data.cidade' => 'required|max:50',
			'data.redes_sociais.*' => 'required|max:50',
			'data.ramo_atuacao' => 'required|max:150',
		];
	}

	public function messages()
	{
		return
			[
				'documento.required' => 'O documento é obrigatório.',
				'documento.digits' => 'O documento deve conter :digits dígitos',
				'documento.unique' => 'Documento já cadastrado',
				'data.nome.required' => 'O nome é obrigatório',
				'data.nome.max' => 'Máximo de :max caracteres',
				'data.email.required' => 'O email é obrigatório',
				'data.email.email' => 'O email deve ser um email válido',
				'data.email.max' => 'Máximo de :max caracteres',
				'data.email.regex' => 'Não é permitido espaço em branco',
				'data.telefone.required' => 'O telefone é obrigatório',
				'data.telefone.digits_between' => 'O telefone deve ter entre :min e :max dígitos.',
				'data.razao_social.required' => 'A razão social é obrigatória',
				'data.razao_social.max' => 'Máximo de :max caracteres',
				'data.endereco.required' => 'O endereço é obrigatório',
				'data.endereco.max' => 'Máximo de :max caracteres',
				'data.uf.required' => 'O estado é obrigatório',
				'data.uf.exists' => 'O estado é inválido',
				'data.cidade.required' => 'A cidade é obrigatória',
				'data.cidade.max' => 'Máximo de :max caracteres',
				'data.redes_sociais.*.required' => 'Campo obrigatório',
				'data.redes_sociais.*.max' => 'Máximo de :max caracteres',
				'data.ramo_atuacao.required' => 'O ramo de atuação é obrigatório',
				'data.ramo_atuacao.max' => 'Máximo de :max caracteres',
			];
	}

	/**
	 * Handle a failed validation attempt.
	 *
	 * @param \Illuminate\Contracts\Validation\Validator|Validator $validator
	 *
	 * @return void
	 */
	protected function failedValidation(Validator $validator)
	{
		$errors = $validator->errors();
		throw new HttpResponseException(
			response()->json(['error_validate' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}
}