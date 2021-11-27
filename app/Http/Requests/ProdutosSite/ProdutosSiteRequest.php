<?php

namespace App\Http\Requests\ProdutosSite;


use App\Helpers\VariableHelper;
use App\Http\Requests\LanguageRequest;
use App\Rules\CheckLanguages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdutosSiteRequest extends LanguageRequest
{
	protected $table_language = 'produtos_site_translation';
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		if(!isset($this->custom_rules)){
			$this->custom_rules = [
				'servicos.*' => ['exists:servicos_site,id'],
				'videos.*' => ['exists:videos_site,id'],
				'languages' => ['required','size:'.DB::table('languages')->count()],
				'languages.*' => [new CheckLanguages],
				'languages.*.title' => ['required','max:50'],
				'languages.*.sub_title' => ['required','max:100'],
				'languages.*.url' => ['required','max:50'],
				'languages.*.link' => ['max:100','url','nullable'],
				'languages.*.text_description_1' => ['required'],
				'languages.*.text_description_2' => ['required'],
			];
		}
		return $this->custom_rules;
	}

	public function messages()
	{
		$messages = [
			'id.required' => 'ID obrigatório',
			'id.exists' => 'ID não existe',
			'languages.size' => 'Precisa ser informado :size linguagens',
			'servicos.*.exists' => 'O serviço não existe',
			'videos.*.exists' => 'O vídeo não existe'
		];
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			$messages["languages.$locale.title.required"] = "Título $locale obrigatório";
			$messages["languages.$locale.title.max"] = "Título $locale máximo de :max caracteres";
			$messages["languages.$locale.sub_title.required"] = "Sub Título $locale obrigatório";
			$messages["languages.$locale.url.required"] = "Url $locale obrigatório";
			$messages["languages.$locale.url.max"] = "Url $locale máximo de :max caracteres";
			$messages["languages.$locale.url.unique"] = "Url $locale já existe";
			$messages["languages.$locale.link.required"] = "Link $locale obrigatório";
			$messages["languages.$locale.link.max"] = "Link $locale máximo de :max caracteres";
			$messages["languages.$locale.link.url"] = "Link $locale precisa ser uma url válida";
			$messages["languages.$locale.sub_title.max"] = "Sub Título $locale máximo de :max caracteres";
			$messages["languages.$locale.text_description_1.required"] = "Descrição 1 $locale obrigatório";
			$messages["languages.$locale.text_description_2.required"] = "Descrição 2 $locale obrigatório";
		}
		return $messages;
	}

	protected function checkExterno($data){
		if(isset($data['externo'])){
			$externo = $data['externo'];
			VariableHelper::convert_string_bool($externo);
			if($externo){
				$this->custom_rules = $this->rules();
				$this->custom_rules['languages.*.link'][] = 'required';
				return;
			}
		}
	}

	protected function validationData()
	{
		$data = $this->all();
		$this->checkExterno($data);
		$this->forceUrl($data);
		$this->uniqueUrlRule();
		return $data;
	}
}
