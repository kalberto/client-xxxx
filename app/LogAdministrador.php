<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/10/2017
 * Time: 18:20
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogAdministrador extends Model
{
	use SoftDeletes;
	protected $table = 'log_administrador';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'registro_id','tabela','tipo','ip','administrador_id','alteracoes'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function administrador(){
		return $this->belongsTo('App\Administrador');
	}

	public function setAlteracoesAttribute($value)
	{
		$this->attributes['alteracoes'] = json_encode($value);
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data){
		$log = new self;
		$log->fill($data);
		if(isset($data['administrador_id']) && ($administrador = Administrador::find($data['administrador_id'])) != null && $log->save()){
			$log->administrador()->associate($administrador);
			$administrador->save();
			return $log->save();
		}
		return false;
	}
}
