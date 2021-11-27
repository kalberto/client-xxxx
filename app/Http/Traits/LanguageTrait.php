<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/10/2020
 * Time: 10:44
 */

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait LanguageTrait
 * @package App\Http\Traits
 * @property array $language
 * @property array $table
 * @method string getTable()
 */
trait LanguageTrait {

	/**
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $builder
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeLanguage($builder){

		$tableName = $this->table;
		$language = DB::table('languages')->where('culture',$this->language)->pluck('id')->first();
		$builder->join($tableName.'_translation',$tableName.'id','=',$tableName.'_translation.owner_id');
		if(!isset($language))
			$language = DB::table('languages')->where('default',1)->pluck('id')->first();
		$builder->where($tableName.'_translation.language_id','=',$language);

		return $builder;
	}
}