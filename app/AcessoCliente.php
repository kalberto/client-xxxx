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

class AcessoCliente extends Model
{
	use SoftDeletes;
	protected $table = 'acesso_cliente';
	public $timestamps  = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ip','data','usuario_id'
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
		$acesso = new self;
		$acesso->fill($data);
		if(isset($data['usuario_id']) && ($usuario = Usuario::find($data['usuario_id'])) != null && $acesso->save()){
			$usuario->acessos()->associate($acesso);
			$usuario->save();
			return $acesso->save();
		}
		return false;
	}
}
