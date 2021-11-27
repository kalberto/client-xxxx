<?php

namespace App\Http\Requests\Paginas;


use App\Http\Requests\LanguageRequest;
use App\Rules\CheckLanguages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaginasRequest extends LanguageRequest
{

	protected $id = 0;
	protected $table_language = 'paginas_translation';
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		if(isset($this->custom_rules))
			return $this->custom_rules;
		else
			return [
				'video_id' => ['nullable','exists:videos_site,id'],
				'languages' => ['required','size:'.DB::table('languages')->count()],
				'languages.*' => [new CheckLanguages],
				'languages.*.title' => ['required','max:65'],
				'languages.*.url' => ['required','max:50'],
				'languages.*.text_1' => ['required'],
				'languages.*.text_2' => ['nullable'],
			];
	}

	public function messages()
	{
		$messages = [
			'id.required' => 'ID obrigatório',
			'id.exists' => 'ID não existe',
			'languages.size' => 'Precisa ser informado :size linguagens',
			'video_id.exists' => 'Media não existe',
		];
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			$messages["languages.$locale.title.required"] = "Título $locale obrigatório";
			$messages["languages.$locale.title.max"] = "Título $locale máximo de :max caracteres";
			$messages["languages.$locale.url.required"] = "Url $locale obrigatório";
			$messages["languages.$locale.url.max"] = "Url $locale máximo de :max caracteres";
			$messages["languages.$locale.url.max"] = "Url $locale já existe";
			$messages["languages.$locale.text_1.required"] = "Texto 1 $locale obrigatório";
			$messages["languages.$locale.text_2.required"] = "Texto 2 $locale obrigatório";
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

	protected function forceUrl(&$data){
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			if(isset($data["languages"][$locale]["url"])){
				$data["languages"][$locale]["url"] = Str::slug($data["languages"][$locale]["url"]);
			}
		}
	}
}
