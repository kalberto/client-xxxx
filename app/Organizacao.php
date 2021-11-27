<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 21/03/2018
 * Time: 15:10
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 17/01/2020
 * Time: 17:10
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nicolaslopezj\Searchable\SearchableTrait;

class Organizacao extends Model
{
	use SearchableTrait;
	use SoftDeletes;

	protected $table = 'organizacao';
	protected $fillable = ['nome','slug'];
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function documentos(){
		return $this->hasMany('App\Documento');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function usuarios(){
		return $this->hasMany('App\Usuario');
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data,$ip) {
		$organizacao = new self;
		$data['slug'] = str_slug($data['nome']);
		$organizacao->fill($data);
		if($organizacao->save()){
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $organizacao->id,
				'tabela' => 'organizacao',
				'tipo' => 'insert',
				'ip' => $ip
			);
			LogAdministrador::store($data);
			return true;
		}
		else
			return false;
	}

	/**
	 * @param $id int
	 * @param $data array
	 *
	 * @return boolean
	 */
	public static function edit($id,$data,$ip){
		$organizacao = self::find($id);
		$data['slug'] = str_slug($data['nome']);
		$organizacao->fill($data);
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $organizacao->id,
			'tabela' => 'organizacao',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $organizacao->save();
	}

	public static function findBySlug($slug){
		$organizacao = Organizacao::where('slug', $slug)->limit(1)->first();
		return $organizacao;
	}

	public static function getOrganizacaoWithEmpresas($slug){
		$organizacao = Organizacao::where('slug', $slug)->limit(1)->first();
		$organizacao['documentos'] = $organizacao->documentos()->pluck('documento');
		return $organizacao;
	}

	public static function getUsuarios($slug){
		$organizacao = Organizacao::findBySlug($slug);
		$usuarios = $organizacao->usuarios()->with('media.media_root')->orderBy('nome')->paginate('20');
		$usuarios->setCollection($usuarios->getCollection()->makeVisible('api_token'));
		return $usuarios;
	}
}
