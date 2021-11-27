<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 26/01/2018
 * Time: *
 */

namespace App;

use App\Notifications\PasswordReset;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class Usuario extends Authenticatable
{
	use Notifiable;
	use SoftDeletes;

	protected $table = 'usuario';

	protected $guard = ['cliente', 'cliente_api'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nome','sobrenome', 'email', 'password','telefone','celular','ativo','api_token','organizacao_id','ultimo_acesso','reset_token','media_id','login'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token','api_token'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'ativo' => 'boolean',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function organizacao(){
		return $this->belongsTo('App\Organizacao');
	}

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
		return $this->belongsToMany('App\ModuloCliente','usuario_has_modulo_cliente','usuario_id','modulo_cliente_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function acessos(){
		return $this->hasMany('App\AcessoCliente');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function endereco(){
		return $this->belongsTo('App\Endereco');
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data,$ip) {
		$usuario = new self;
		if(!isset($data['api_token']))
			$data['api_token'] = str_random(46).date("YmdHis");
		$usuario->fill($data);
		if($usuario->save() ){
			if(isset($data['permissions'])){
				foreach ($data['permissions'] as $key => $c){
					if($c){
						$modulo = ModuloCliente::find($key);
						$usuario->modulos()->attach($modulo);
					}
				}
			}
			if(isset($data['media_id'])){
				$media = Media::find($data['media_id']);
				$usuario->media()->associate($media);
				$media->make_real();
			}
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $usuario->id,
			'tabela' => 'usuario',
			'tipo' => 'insert',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $usuario->save();
	}

	/**
	 * @param $id int
	 * @param $data array
	 *
	 * @return boolean
	 */
	public static function edit($id,$data, $ip){
		$usuario = self::find($id);
		$data['login'] = $usuario->login;
		$data['email'] = $usuario->email;
		$data['api_token'] = $usuario->api_token;
		if(isset($data['media_id']) && is_numeric($data['media_id'])){
			$media = Media::find($data['media_id']);
			if($usuario->media()->first() != null)
				$old_media_id = $usuario->media()->first()->id;
			if(isset($old_media_id) && $media->id != $old_media_id){
				$old_media = Media::find($old_media_id);
				$usuario->fill($data);
				$usuario->media()->associate($media);
				$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
				$usuario->save();
				$old_media->delete();
			}else{
				if(!isset($old_media_id)){
					$usuario->fill($data);
					$usuario->media()->associate($media);
					$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
					$usuario->save();
				}else{
					$usuario->fill($data);
					$usuario->save();
				}
			}
		}elseif($usuario->media()->first() != null){
			$media = $usuario->media()->first();
			$usuario->media()->dissociate();
			$usuario->fill($data);
			$usuario->save();
			$media->delete();
		}else{
			$usuario->fill($data);
		}
		if(isset($data['permissions'])){
			$permissions = [];
			foreach ($data['permissions'] as $key => $var){
				if($var)
					$permissions[] = $key;
			}
			$usuario->modulos()->sync($permissions);
		}else{
			$usuario->modulos()->sync([]);
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $usuario->id,
			'tabela' => 'usuario',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $usuario->save();
	}

	public function clientEdit($data, $ip){
		$data['email'] = $this->email;
		$data['login'] = $this->login;
		$data['api_token'] = $this->api_token;
		if(isset($data['media_id']) && is_numeric($data['media_id'])){
			$media = Media::find($data['media_id']);
			if($this->media()->first() != null)
				$old_media_id = $this->media()->first()->id;
			if(isset($old_media_id) && $media->id != $old_media_id){
				$old_media = Media::find($old_media_id);
				$this->fill($data);
				$this->media()->associate($media);
				$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
				$this->save();
				$old_media->delete();
			}else{
				if(!isset($old_media_id)){
					$this->fill($data);
					$this->media()->associate($media);
					$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
					$this->save();
				}else{
					$this->fill($data);
					$this->save();
				}
			}
		}elseif($this->media()->first() != null){
			$media = $this->media()->first();
			$this->media()->dissociate();
			$this->fill($data);
			$this->save();
			$media->delete();
		}else{
			$this->fill($data);
			$this->save();
		}
		$dado = array(
			'usuario_id' => $this->id,
			'ip' => $ip,
			'acao' => 'Editar',
			'area' => 'Usuario'
		);
		LogUsuario::store($dado);
		return $this->save();
	}

	public static function editPassAdm($id, $data, $ip){
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

	public function editPass($data, $ip){
		$this->password = $data['new_password'];
		$dado = array(
			'usuario_id' => $this->id,
			'ip' => $ip,
			'acao' => 'Editar',
			'area' => 'Senha'
		);
		LogUsuario::store($dado);
		return $this->save();
	}

	public function menus(){
		return $this->modulos()->orderBy('nome')->get();
	}

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	public static function getUserWithMenu($id){
		$usuario = Usuario::with('media.media_root')->find($id);
		$permissions = array();
		foreach ($usuario->modulos as $modulo){
			$permissions[$modulo->id] = true;
		}
		$usuario['permissions'] = $permissions;
		return $usuario;
	}

	public static function countTotal(){
		return self::count();
	}

	public static function login($id,$request){
		try{
			$user = Usuario::find($id);
			$acesso = new AcessoCliente();
			$acesso->data = Carbon::now();
			$acesso->ip = $request->ip();
			$acesso->usuario()->associate($user);
			$acesso->save();
			$user->ultimo_acesso = Carbon::now();
			$user->save();
			return true;
		}catch (Exception $e){
			return false;
		}
	}

	public static function getByApiToken($api_token){
		$usuario = Usuario::where('api_token', $api_token)->limit(1)->first();
		return $usuario;
	}

	public static function getByLogin($login){
		$usuario = Usuario::where('login', $login)->limit(1)->first();
		return $usuario;
	}

	public static function getByResetToken($token){
		$usuario = Usuario::where('reset_token', $token)->limit(1)->first();
		return $usuario;
	}

	public function getEnderecoOrCreate(){
		$endereco = $this->endereco()->get()->toArray();
		if(!$endereco){
			$endereco = new Endereco();
			$endereco->uf_id = 1;
			$endereco->save();
			$this->endereco()->associate($endereco);
			$this->save();
			$endereco = $this->endereco()->get()->toArray();
		}
		return $endereco[0];
	}

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendResetPasswordNotification($token,$nome)
	{
		$this->notify(new PasswordReset($token,$nome));
	}
}
