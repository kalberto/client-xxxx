<?php

namespace App\Models\Site;

use App\Helpers\VariableHelper;
use App\Http\Traits\LanguageTrait;
use App\Http\Traits\ModelLogTrait;
use App\Models\Site\Translations\PaginasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static Paginas find($id)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class Paginas extends Model
{
	use LanguageTrait,ModelLogTrait;

	protected $table = 'paginas';
	protected $fillable = ['video_id','languages','videos'];

	public function setLanguagesAttribute($value){
		$languages = DB::table('languages')->get();
		foreach ($languages as $language){
			if(isset($value[$language->locale])){
				$value[$language->locale]['owner_id'] = $this->id;
				$value[$language->locale]['language_id'] = $language->id;
				PaginasTranslations::editOrCreate($value[$language->locale]);
			}
		}
	}

	public function setVideosAttribute($value){
		DB::table('paginas_has_videos_site')->where('pagina_id','=',$this->id)->delete();
		foreach ($value as $item){
			DB::table('paginas_has_videos_site')->insert([
				'pagina_id' => $this->id,
				'video_id' => $item
			]);
		}
	}

	public function getLanguagesAttribute(){
		$languages = [];
		$languages_db = DB::table('languages')->get();
		foreach ($languages_db as $language){
			$translation = DB::table('paginas_translation')->select(['title','url','sub_title','text_1','text_2'])
			                                                     ->where([['owner_id','=',$this->id],['language_id','=',$language->id]])->first();
			$languages[$language->locale] = $translation;
		}
		return $languages;
	}

	public function getVideosAttribute(){
		$videos = DB::table('paginas_has_videos_site')->where('pagina_id','=',$this->id)->pluck('video_id')->toArray();
		return $videos;
	}

	public static function store($data,$ip) {
		$registro = new self;
		if($registro->save()){
			$registro->fill($data);
			$registro->save();
			$registro->saveLog($ip,'insert',$data);
			return $registro;
		}
		return false;
	}

	public static function edit($id,$data,$ip){
		$registro = self::find($id);
		$registro->fill($data);
		if($registro->save()){
			$registro->saveLog($ip,'update',$data);
			return $registro;
		}
		return false;
	}

	public function remove($ip){
		$this->deleteLog($ip);
		PaginasTranslations::where('owner_id','=',$this->id)->delete();
		return $this->delete();
	}
}
