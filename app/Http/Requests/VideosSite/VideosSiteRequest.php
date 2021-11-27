<?php

namespace App\Http\Requests\VideosSite;


use App\Rules\CheckLanguages;
use Illuminate\Support\Facades\DB;

class VideosSiteRequest extends Request
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'languages' => ['required','size:'.DB::table('languages')->count()],
			'languages.*' => [new CheckLanguages],
			'languages.*.title' => ['required','max:50'],
			'languages.*.text_description' => ['required'],
			'thumb_id' => ['exists:media,id','nullable'],
			'video_id' => ['exists:media,id','nullable']
		];
	}

	public function messages()
	{
		$rules = [
			'id.required' => 'ID obrigatório',
			'id.exists' => 'ID não existe',
			'languages.size' => 'Precisa ser informado :size linguagens',
			'thumb_id.exists' => 'Media não existe',
			'video_id.exists' => 'Media não existe',
		];
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			$rules["languages.$locale.title.required"] = "Título $locale obrigatório";
			$rules["languages.$locale.title.max"] = "Título $locale máximo de :max caracteres";
			$rules["languages.$locale.text_description.required"] = "Descrição $locale obrigatório";
		}
		return $rules;
	}
}
