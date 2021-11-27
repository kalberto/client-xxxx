<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 16/10/2020
 * Time: 15:29
 */

namespace App\Http\Requests;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LanguageRequest extends Request {

	protected $unique_rule = '';
	protected $custom_rules = null;
	protected $table_language = '';

	protected function getLanguageId($locale){
		$language = DB::table('languages')->where('locale','=',$locale)->first();
		if(isset($language))
			return $language->id;
		return false;
	}

	protected function forceUrl(&$data){
		$locales = DB::table('languages')->pluck('locale')->toArray();
		foreach ($locales as $locale){
			if(isset($data["languages"][$locale]["url"])){
				$data["languages"][$locale]["url"] = Str::slug($data["languages"][$locale]["url"]);
			}
		}
	}

	protected function uniqueUrlRule($id = null){
		$languages = DB::table('languages')->get()->toArray();
		$this->custom_rules = $this->rules();
		foreach ($languages as $language){
			$search_id = null;
			$item = DB::table($this->table_language)->where([['language_id','=',$language->id],['owner_id','=',$id]])->first();
			if(isset($item))
				$search_id = $item->id;
			$this->custom_rules["languages.$language->locale.url"][] = Rule::unique($this->table_language,'url')->where('language_id',$language->id)->ignore($search_id);
		}
	}
}