<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 09/11/2017
 * Time: 09:17
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/02/2017
 * Time: 15:12
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Manual extends Model
{
	protected $table = 'manual';
	protected $fillable = ['nome','media_id','ativo','tipo_servico','tipo_servico_url'];
	protected $hidden = [];
	protected $casts = [
		'ativo' => 'boolean',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media(){
		return $this->belongsTo('App\Media');
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data, $ip) {
		$manual = new self;
		$manual->fill($data);
		if(isset($data['media_id']) && is_numeric($data['media_id']) && $manual->save()){
			$media = Media::find($data['media_id']);
			$manual->media()->associate($media);
			$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
		}
		if($manual->save()){
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $manual->id,
				'tabela' => 'manual',
				'tipo' => 'insert',
				'ip' => $ip
			);
			LogAdministrador::store($data);
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * @param $id int
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function edit($id,$data) {
		$manual = self::find($id);
		if(isset($data['media_id']) && is_numeric($data['media_id']) && $manual->save()){
			$media = Media::find($data['media_id']);
			if($manual->media()->first() != null)
				$old_media_id = $manual->media()->first()->id;
			if(isset($old_media_id) && $media->id != $old_media_id){
				$old_media = Media::find($old_media_id);
				$manual->fill($data);
				$manual->media()->associate($media);
				$media->make_real(isset($data['legenda']) ? $data['legenda'] : null);
				$old_media->delete();
			}else{
				if(!isset($old_media_id)) {
					$manual->fill( $data );
					$manual->media()->associate( $media );
					$media->make_real( isset( $data['legenda'] ) ? $data['legenda'] : null );
				}else{
					$manual->fill( $data );
				}
			}
		}elseif($manual->media()->first() != null){
			$media = $manual->media()->first();
			$manual->media()->dissociate();
			$manual->save();
			$media->delete();
		}
		return $manual->save();
	}

	public function delete() {
		$media = $this->media()->first();
		if(isset($media)){
			$this->media()->dissociate();
			$this->save();
			$media->delete();
		}
		return parent::delete();
	}

	public static function getByServico($url){
		$manuais = Manual::where('tipo_servico_url' ,$url)->orderBy('nome')->paginate(20);
		return $manuais;
	}

	public static function getByServicoName($name){
		$manuais = Manual::where('tipo_servico' ,$name)->orderBy('nome')->paginate(20);
		return $manuais;
	}

	public static function getByServicos($url){
		$manuais = Manual::with('media.media_root')->whereIn('tipo_servico_url' ,$url)->orderBy('nome');
		return $manuais;
	}

	public static function getByServicosName($name){
		$manuais = Manual::with('media.media_root')->whereIn('tipo_servico' ,$name)->orderBy('nome');
		return $manuais;
	}

	public static function getByServicoUrl($url,$limit = 5){
		$manuais = Manual::with('media.media_root')->where('tipo_servico_url' ,$url)->orderBy('nome')->limit($limit)->get();
		return $manuais;
	}

	public static function countTotal(){
		return self::count();
	}
}
