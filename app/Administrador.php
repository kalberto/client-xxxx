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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Nicolaslopezj\Searchable\SearchableTrait;

class Administrador extends Authenticatable
{
	use Notifiable;
	use SoftDeletes;
	use SearchableTrait;

	protected $table = 'administrador';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nome','sobrenome', 'email', 'password','telefone','celular','ativo','api_token','ultimo_acesso','media_id'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token','api_token',
	];
	protected $casts = [
		'ativo' => 'boolean',
	];

	/**
	 * The attributes that are searchables
	 *
	 * @var array
	 * */
	protected $searchable = [
		'columns' => [
			'administrador.nome' => 10,
			'administrador.sobrenome' => 5,
			'administrador.telefone' => 10,
			'administrador.email' => 5,
			'administrador.ultimo_acesso' => 5
		]
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media(){
		return $this->belongsTo('App\Media');
	}

	/**
	 * Get the modulos for the administrador.
	 */
	public function modulos()
	{
		return $this->belongsToMany('App\ModuloGerenciador','administrador_has_modulo_gerenciador','administrador_id','modulo_gerenciador_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function acessos(){
		return $this->hasMany('App\AcessoAdministrador');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function logs(){
		return $this->hasMany('App\LogAdministrador');
	}
	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data,$ip) {
		$administrador = new self;
		if(!isset($data['api_token']))
			$data['api_token'] = str_random(46).date("YmdHis");
		$administrador->fill($data);
		if(isset($data['media_id']) && is_numeric($data['media_id']) && $administrador->save()){
			$media = Media::find($data['media_id']);
			$administrador->media()->associate($media);
			$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
		}
		if($administrador->save() && isset($data['permissions'])){
			$array = array();
			foreach ($data['permissions'] as $key => $c) {
				if ($c)
					$array[] = $key;
			}
			$administrador->modulos()->sync($array);
		}else{
			$administrador->modulos()->sync([]);
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $administrador->id,
			'tabela' => 'administrador',
			'tipo' => 'insert',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $administrador->save();
	}

	/**
	 * @param $id int
	 * @param $data array
	 *
	 * @return boolean
	 */
	public static function edit($id,$data,$ip){
		$administrador = self::find($id);
		$data['email'] = $administrador->email;
		$data['api_token'] = $administrador->api_token;
		if(isset($data['media_id']) && is_numeric($data['media_id'])){
			$media = Media::find($data['media_id']);
			if($administrador->media()->first() != null)
				$old_media_id = $administrador->media()->first()->id;
			if(isset($old_media_id) && $media->id != $old_media_id){
				$old_media = Media::find($old_media_id);
				$administrador->fill($data);
				$administrador->media()->associate($media);
				$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
				$administrador->save();
				$old_media->delete();
			}else{
				if(!isset($old_media_id)){
					$administrador->fill($data);
					$administrador->media()->associate($media);
					$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
					$administrador->save();
				}else{
					$administrador->fill($data);
					$administrador->save();
				}
			}
		}elseif($administrador->media()->first() != null){
			$media = $administrador->media()->first();
			$administrador->media()->dissociate();
			$administrador->fill($data);
			$administrador->save();
			$media->delete();
		}else{
			$administrador->fill($data);
		}
		if(isset($data['permissions'])){
			$array = array();
			foreach ($data['permissions'] as $key => $c) {
				if ($c)
					$array[] = $key;
			}
			$administrador->modulos()->sync($array);
		}else{
			$administrador->modulos()->sync([]);
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $administrador->id,
			'tabela' => 'administrador',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $administrador->save();
	}

	public static function editPass($id, $data, $ip){
		$administrador = self::find($id);
		$new_data['password'] = $data['password'];
		$administrador->fill($data);
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $administrador->id,
			'tabela' => 'administrador',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $administrador->save();
	}

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	public static function login($id, $request){
        $adm = Administrador::find($id);
        $acesso = new AcessoAdministrador();
        $acesso->data = Carbon::now();
        $acesso->ip = $request->ip();
        $acesso->administrador()->associate($adm);
        $acesso->save();
        $adm->ultimo_acesso = Carbon::now();
        $adm->save();
        return true;
	}

	public function menus(){
		return $this->modulos()->orderBy('order')->get();
	}

	public static function getUserWithMenu($id){
		$administrador = Administrador::with('media.media_root')->find($id);
		$permissions = array();
		foreach ($administrador->modulos as $modulo){
			$permissions[$modulo->id] = true;
		}
		$administrador['permissions'] = $permissions;
		return $administrador;
	}

	public static function countTotal(){
		return self::count();
	}
}
