<?php

namespace App\Models\Site;

use App\Helpers\VariableHelper;
use App\Http\Traits\LanguageTrait;
use App\Http\Traits\ModelLogTrait;
use App\Models\Site\Translations\ProdutosTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static Produtos find($id)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class Produtos extends Model
{
	use LanguageTrait,ModelLogTrait;

	protected $table = 'produtos_site';
	protected $fillable = ['ativo','externo','languages','servicos','videos'];

	public function setAtivoAttribute($value){
		$this->attributes['ativo'] = true;
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
				ProdutosTranslations::editOrCreate($value[$language->locale]);
			}
		}
	}

	public function setServicosAttribute($value){
		DB::table('produtos_has_servicos_site')->where('produto_id','=',$this->id)->delete();
		foreach ($value as $item){
			DB::table('produtos_has_servicos_site')->insert([
				'produto_id' => $this->id,
				'servico_id' => $item
			]);
		}
	}

	public function setVideosAttribute($value){
		DB::table('produtos_has_videos_site')->where('produto_id','=',$this->id)->delete();
		foreach ($value as $item){
			DB::table('produtos_has_videos_site')->insert([
				'produto_id' => $this->id,
				'video_id' => $item
			]);
		}
	}

	public function getLanguagesAttribute(){
		$languages = [];
		$languages_db = DB::table('languages')->get();
		foreach ($languages_db as $language){
			$translation = DB::table('produtos_site_translation')->select(['title','sub_title','url','link','text_description_1','text_description_2'])
			                                                     ->where([['owner_id','=',$this->id],['language_id','=',$language->id]])->first();
			$languages[$language->locale] = $translation;
		}
		return $languages;
	}

	public function getServicosAttribute(){
		$servicos = DB::table('produtos_has_servicos_site')->where('produto_id','=',$this->id)->pluck('servico_id')->toArray();
		return $servicos;
	}

	public function getVideosAttribute(){
		$videos = DB::table('produtos_has_videos_site')->where('produto_id','=',$this->id)->pluck('video_id')->toArray();
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
		if(!isset($data['servicos']))
			$data['servicos'] = [];
		if($registro->save()){
			$registro->saveLog($ip,'update',$data);
			return $registro;
		}
		return false;
	}

	public function remove($ip){
		$this->deleteLog($ip);
		ProdutosTranslations::where('owner_id','=',$this->id)->delete();
		return $this->delete();
	}
}
