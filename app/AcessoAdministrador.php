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

class AcessoAdministrador extends Model
{
	use SoftDeletes;
	protected $table = 'acesso_administrador';
	public $timestamps  = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ip','data','administrador_id'
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

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data){
		$acesso = new self;
		$acesso->fill($data);
		if(isset($data['administrador_id']) && ($administrador = Administrador::find($data['administrador_id'])) != null && $acesso->save()){
			$administrador->acessos()->associate($acesso);
			$administrador->save();
			return $acesso->save();
		}
		return false;
	}
}
