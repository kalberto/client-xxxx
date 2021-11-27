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

class ModuloGerenciador extends Model
{
	use SoftDeletes;

	protected $table = 'modulo_gerenciador';
	protected $fillable = ['nome','icone','modulo_list','modulo_url','order'];
	protected $hidden = [];

	/**
	 * Get the usuarios for the modulo.
	 */
	public function administradores(){
		return $this->belongsToMany('App\Administradores','administrador_has_modulo_gerenciador','modulo_gerenciador_id','administrador_id');
	}

	public static function getAll(){
		return ModuloGerenciador::query()->orderBy('order')->get();
	}
}
