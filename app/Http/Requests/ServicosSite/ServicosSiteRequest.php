<?php

namespace App\Http\Requests\ServicosSite;


use App\Http\Requests\LanguageRequest;
use App\Rules\CheckLanguages;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServicosSiteRequest extends LanguageRequest
{
	protected $table_language = 'servicos_site_translation';
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		if(!isset($this->custom_rules)){
			$this->custom_rules = [
				'videos.*' => ['exists:videos_site,id'],
				'languages' => ['required','size:'.DB::table('languages')->count()],
				'languages.*' => [new CheckLanguages],
				'languages.*.title' => ['required','max:50'],
				'languages.*.sub_title' => ['required','max:100'],
				'languages.*.url' => ['required','max:50'],
				'languages.*.text_description_1' => ['required'],
				'languages.*.text_description_2' => ['required'],
				'languages.*.benefits' => ['required'],
				'languages.*.differentials' => ['required'],
				'media_id' => ['exists:media,id','nullable']
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
			'media_id.exists' => 'Media não existe',
			'videos.*.exists' => 'O vídeo não existe'
		];
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			$messages["languages.$locale.title.required"] = "Título $locale obrigatório";
			$messages["languages.$locale.title.max"] = "Título $locale máximo de :max caracteres";
			$messages["languages.$locale.sub_title.required"] = "Sub Título $locale obrigatório";
			$messages["languages.$locale.sub_title.max"] = "Sub Título $locale máximo de :max caracteres";
			$messages["languages.$locale.url.required"] = "Url $locale obrigatório";
			$messages["languages.$locale.url.max"] = "Url $locale máximo de :max caracteres";
			$messages["languages.$locale.url.unique"] = "Url $locale já existe";
			$messages["languages.$locale.text_description_1.required"] = "Descrição 1 $locale obrigatório";
			$messages["languages.$locale.text_description_2.required"] = "Descrição 2 $locale obrigatório";
			$messages["languages.$locale.benefits.required"] = "Benefícios $locale obrigatório";
			$messages["languages.$locale.differentials.required"] = "Diferenciais $locale obrigatório";
		}
		return $messages;
	}

	protected function validationData()
	{
		$data = $this->all();
		$this->forceUrl($data);
		$this->uniqueUrlRule();
		return $data;
	}
}
