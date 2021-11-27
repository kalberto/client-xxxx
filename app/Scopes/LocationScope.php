<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/10/2020
 * Time: 10:44
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class LocationScope implements Scope{

	private $language;
	public function __construct($p_language = false) {
		$this->language = $p_language;
	}

	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function apply(Builder $builder, Model $model)
	{
		$tableName = $model->getTable();
		$language = DB::table('languages')->where('culture',$this->language)->pluck('id')->first();
		$builder->join($tableName.'_translation',$tableName.'id','=',$tableName.'_translation.owner_id');
		if(isset($language))
			$builder->where($tableName.'_translation.language_id','=',$language);
		else
			$builder->where($tableName.'_translation.default','=',1);
	}
}