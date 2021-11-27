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

class ModuloCliente extends Model
{
	use SoftDeletes;

	protected $table = 'modulo_cliente';
	protected $fillable = ['nome','menu','url','order','parent_id'];
	protected $hidden = ['id','parent_id','deleted_at','pivot'];

	/**
	 * Get the usuarios for the modulo.
	 */
	public function usuarios(){
		return $this->belongsToMany('App\Usuario','usuario_has_modulo_cliente','modulo_cliente_id','usuario_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent(){
		return $this->belongsTo('App\ModuloCliente','parent_id');
	}

	/**
	 * Get the children for the modulo
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function children(){
		return $this->hasMany('App\Categoria');
	}

	public static function getAll(){
		return ModuloCliente::query()->orderBy('nome')->get();
	}
}
