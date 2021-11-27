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

class LogUsuario extends Model
{
	use SoftDeletes;
	protected $table = 'log_usuario';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'area','acao','ip','usuario_id'
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
	public function usuario(){
		return $this->belongsTo('App\Usuario');
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data){
		$log = new self;
		$log->fill($data);
		if(isset($data['usuario_id']) && ($usuario = Usuario::find($data['usuario_id'])) != null && $log->save()){
			$log->usuario()->associate($usuario);
			$usuario->save();
			return $log->save();
		}
		return false;
	}
}
