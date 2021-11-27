<?php

namespace App\Models\Site;

use App\Helpers\VariableHelper;
use App\Http\Traits\LanguageTrait;
use App\Http\Traits\ModelLogTrait;
use App\Media;
use App\Models\Site\Translations\ServicosTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static Servicos find($id)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class Servicos extends Model
{
	use LanguageTrait,ModelLogTrait;

	protected $table = 'servicos_site';
	protected $fillable = ['ativo','externo','media_id','languages','videos','servicos'];

	public function setAtivoAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['ativo'] = $value;
	}

	public function setExternoAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['externo'] = $value;
	}


	public function setLanguagesAttribute($value){
		$languages = DB::table('languages')->get();
		foreach ($languages as $language){
			if(isset($value[$language->locale])){
				$value[$language->locale]['owner_id'] = $this->id;
				$value[$language->locale]['language_id'] = $language->id;
				ServicosTranslations::editOrCreate($value[$language->locale]);
			}
		}
	}

	public function setVideosAttribute($value){
		DB::table('servicos_has_videos_site')->where('servico_id','=',$this->id)->delete();
		foreach ($value as $item){
			DB::table('servicos_has_videos_site')->insert([
				'servico_id' => $this->id,
				'video_id' => $item
			]);
		}
	}

	public function setServicosAttribute($value){
		DB::table('servicos_site_has_servicos_site')->where('servico_id','=',$this->id)->delete();
		foreach ($value as $item){
			if($item != $this->id){
				DB::table('servicos_site_has_servicos_site')->insert([
					'servico_id' => $this->id,
					'servico_relacionado_id' => $item
				]);
			}
		}
	}

	public function getLanguagesAttribute(){
		$languages = [];
		$languages_db = DB::table('languages')->get();
		foreach ($languages_db as $language){
			$translation = DB::table('servicos_site_translation')->select(['title','sub_title','url','link','text_description_1','text_description_2','benefits','differentials'])
			                                                     ->where([['owner_id','=',$this->id],['language_id','=',$language->id]])->first();
			$languages[$language->locale] = $translation;
		}
		return $languages;
	}

	public function getVideosAttribute(){
		$videos = DB::table('servicos_has_videos_site')->where('servico_id','=',$this->id)->pluck('video_id')->toArray();
		return $videos;
	}

	public function getServicosAttribute(){
		$servicos = DB::table('servicos_site_has_servicos_site')->where('servico_id','=',$this->id)->pluck('servico_relacionado_id')->toArray();
		return $servicos;
	}

	public function getFileAttribute(){
		if(!isset($this->media_id))
			return false;
		$media = DB::table('media')->join('media_root','media.media_root_id','=','media_root.id')->where('media.id','=',$this->media_id)->first();
		return url($media->path.$media->nome);
	}

	public static function store($data,$ip) {
		$registro = new self;
		if($registro->save()){
			$registro->fill($data);
			$registro->save();
			if(isset($data['media_id'])){
				$media = Media::find($data['media_id']);
				$media->make_real();
			}
			$registro->saveLog($ip,'insert',$data);
			return $registro;
		}
		return false;
	}

	public static function edit($id,$data,$ip){
		$registro = self::find($id);
		if(!isset($data['videos']))
			$data['videos'] = [];
		if(isset($registro->media_id)){
			$media = null;
			if(isset($data['media_id']))
				$media = Media::find($data['media_id']);
			if(!isset($media) || $media->id != $registro->media_id){
				$old_media = Media::find($registro->media_id);
				$registro->media_id = null;
				$registro->save();
				$old_media->delete();
			}
		}else{
			if(isset($data['media_id'])){
				$media = Media::find($data['media_id']);
			}
		}
		if(isset($media)){
			$registro->media_id = $media->id;
			$media->make_real();
		}
		$registro->fill($data);
		if($registro->save()){
			$registro->saveLog($ip,'update',$data);
			return $registro;
		}
		return false;
	}

	public function remove($ip){
		$this->deleteLog($ip);
		ServicosTranslations::where('owner_id','=',$this->id)->delete();
		return $this->delete();
	}
}
