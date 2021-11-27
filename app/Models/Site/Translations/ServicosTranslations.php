<?php

namespace App\Models\Site\Translations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static ServicosTranslations find($id)
 * @method static ServicosTranslations UpdateOrCreate(array $finder, array $fill)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class ServicosTranslations extends Model
{

	protected $table = 'servicos_site_translation';
	protected $fillable = ['owner_id','language_id','title','sub_title','url','link','text_description_1','text_description_2','benefits','differentials'];
	public $timestamps = false;

	public function setUrlAttribute($value){
		$this->attributes['url'] = Str::slug($value);
	}

	public static function editOrCreate($data){
		$registro = self::UpdateOrCreate(
			[
				'owner_id' => $data['owner_id'],
				'language_id' => $data['language_id']
			],
			[
				'title' => $data['title'],
				'url' => Str::slug($data['url']),
				'sub_title' => $data['sub_title'],
				'link' => isset($data['link']) ? $data['link'] : '',
				'text_description_1' => isset($data['text_description_1']) ? $data['text_description_1'] : '',
				'text_description_2' => isset($data['text_description_2']) ? $data['text_description_2'] : '',
				'benefits' => isset($data['benefits']) ? $data['benefits'] : '',
				'differentials' => isset($data['differentials']) ? $data['differentials'] : '',
			]);
		$registro->save();
	}
}