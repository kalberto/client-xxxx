<?php

namespace App\Models\Site;

use App\Helpers\VariableHelper;
use App\Http\Traits\LanguageTrait;
use App\Http\Traits\ModelLogTrait;
use App\Media;
use App\Models\Site\Translations\VideosTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static Videos find($id)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class Videos extends Model
{
	use LanguageTrait,ModelLogTrait;

	protected $table = 'videos_site';
	protected $fillable = ['ativo','thumb_id','video_id','languages'];

	public function setAtivoAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['ativo'] = $value;
	}

	public function setLanguagesAttribute($value){
		$languages = DB::table('languages')->get();
		foreach ($languages as $language){
			if(isset($value[$language->locale])){
				$value[$language->locale]['owner_id'] = $this->id;
				$value[$language->locale]['language_id'] = $language->id;
				VideosTranslations::editOrCreate($value[$language->locale]);
			}
		}
	}

	public function getLanguagesAttribute(){
		$languages = [];
		$languages_db = DB::table('languages')->get();
		foreach ($languages_db as $language){
			$translation = DB::table('videos_site_translation')->select(['title','text_description'])
			                                                     ->where([['owner_id','=',$this->id],['language_id','=',$language->id]])->first();
			$languages[$language->locale] = $translation;
		}
		return $languages;
	}

	public function getThumbAttribute(){
		if(!isset($this->thumb_id))
			return false;
		$media = DB::table('media')->join('media_root','media.media_root_id','=','media_root.id')->where('media.id','=',$this->thumb_id)->first();
		return url($media->path.$media->nome);
	}

	public function getVideoAttribute(){
		if(!isset($this->video_id))
			return false;
		$media = DB::table('media')->join('media_root','media.media_root_id','=','media_root.id')->where('media.id','=',$this->video_id)->first();
		return url($media->path.$media->nome);
	}

	public static function store($data,$ip) {
		$registro = new self;
		if($registro->save()){
			$registro->fill($data);
			$registro->save();
			if(isset($data['thumb_id'])){
				$media = Media::find($data['thumb_id']);
				$media->make_real();
			}
			if(isset($data['video_id'])){
				$media = Media::find($data['video_id']);
				$media->make_real();
			}
			$registro->saveLog($ip,'insert',$data);
			return $registro;
		}
		return false;
	}

	public static function edit($id,$data,$ip){
		$registro = self::find($id);
		$registro->fill($data);
		if($registro->save()){
			if(isset($data['thumb_id'])){
				$media = Media::find($data['thumb_id']);
				$media->make_real();
			}
			if(isset($data['video_id'])){
				$media = Media::find($data['video_id']);
				$media->make_real();
			}
			$registro->saveLog($ip,'update',$data);
			return $registro;
		}
		return false;
	}

	public function remove($ip){
		$this->deleteLog($ip);
		VideosTranslations::where('owner_id','=',$this->id)->delete();
		return $this->delete();
	}
}
