<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Endereco extends Model
{
	use SoftDeletes;
	public $timestamps  = false;

	protected $table = 'endereco';

	protected $guard = ['cliente', 'cliente_api'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'cep','uf_id', 'cidade', 'endereco','numero','complemento','bairro'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'id','deleted_at'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function usuarios(){
		return $this->hasMany('App\Usuario');
	}

	public static function getUfs(){
		$ufs = DB::table('uf')->get()->toArray();
		return $ufs;
	}

	public static function getUf($uf){
		$uf = DB::table('uf')->where('uf',$uf)->first();
		return $uf;
	}

	public static function edit($id, $dados){
		if(isset($id) && is_numeric($id) && ($endereco = Endereco::find($id)) != null){
			if(isset($dados['uf'])){
				$uf = Endereco::getUf($dados['uf']);
				$dados['uf_id'] = $uf->id;
			}
			$endereco->fill($dados);
			return $endereco->save();
		}
		return false;
	}
}
