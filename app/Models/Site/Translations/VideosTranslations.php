<?php

namespace App\Models\Site\Translations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static VideosTranslations find($id)
 * @method static VideosTranslations UpdateOrCreate(array $finder, array $fill)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class VideosTranslations extends Model
{

	protected $table = 'videos_site_translation';
	protected $fillable = ['owner_id','language_id','title','text_description'];
	public $timestamps = false;

	public static function editOrCreate($data){
		$registro = self::UpdateOrCreate(
			[
				'owner_id' => $data['owner_id'],
				'language_id' => $data['language_id']
			],
			[
				'title' => $data['title'],
				'text_description' => $data['text_description'],
			]);
		$registro->save();
	}
}