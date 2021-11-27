<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/11/2017
 * Time: 18:19
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 13/11/2017
 * Time: *
 */

namespace App;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Exception;

class Media extends Model
{
	protected $table = 'media';
	protected $fillable = ['nome','nome_temp','viewport','legenda','temp','media_root_id'];
	protected $hidden = [];
	protected $casts = [
		'ativo' => 'boolean',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media_root(){
		return $this->belongsTo('App\MediaRoot');
	}

	/**
	 * Get the paginas for the media - One to many.
	 */
	public function manuais(){
		return $this->hasMany('App\Manual');
	}

	/**
	 * Get the administradores for the media - One to many.
	 */
	public function administradores(){
		return $this->hasMany('App\Administrador');
	}

	/**
	 * Get the usuarios for the media - One to many.
	 */
	public function usuarios(){
		return $this->hasMany('App\Usuario');
	}

	public function make_real($legenda = null){
        if($this->temp) {
            $this->temp = false;
            if(isset($legenda))
                $this->legenda = $legenda;
            $media_root = $this->media_root()->first();
            $path       = $media_root->path;
            MediaHelper::move_file( $path . 'temp/', $path, $this->nome, null );
            $resizes = $media_root->media_resizes()->get();
            foreach ( $resizes as $resize ) {
                $comp_path = $media_root->path . $resize->path;
                MediaHelper::move_file( $comp_path . 'temp/', $comp_path, $this->nome, null );
            }
            $this->save();
        }
        else{
            if(isset($this->nome_temp)){
                if(isset($legenda))
                    $this->legenda = $legenda;
                $media_root = $this->media_root()->first();
                $path       = $media_root->path;
                MediaHelper::delete_file($path,$this->nome);
                MediaHelper::move_file( $path . 'temp/', $path, $this->nome_temp, null );
                $resizes = $media_root->media_resizes()->get();
                foreach ( $resizes as $resize ) {
                    $comp_path = $media_root->path . $resize->path;
                    MediaHelper::delete_file($comp_path,$this->nome);
                    MediaHelper::move_file( $comp_path . 'temp/', $comp_path, $this->nome_temp, null );
                }
                $this->nome = $this->nome_temp;
                $this->nome_temp = null;
                $this->save();
            }
        }
        return true;
	}

	public static function getMediaWithRoot($id){
		$media = Media::with('media_root')->find($id);
		if($media->temp){
			$media->media_root->path .= 'temp/';
		}
		return $media;
	}

	/**
	 * @return bool|null
	 */
	public function delete() {
		$modulo = $this->media_root()->first();
		$resizes = $modulo->media_resizes()->get();
		if($this->temp)
			$path = 'temp/';
		else
			$path = '';
		$base_path = $modulo->path.$path;
		MediaHelper::delete_file( $base_path, $this->nome );
		if(isset($this->nome_temp))
			MediaHelper::delete_file($base_path.'temp/', $this->nome_temp);
		foreach ( $resizes as $resize ) {
			$comp_path = $modulo->path.$resize->path.$path;
			MediaHelper::delete_file( $comp_path, $this->nome );
			if(isset($this->nome_temp))
				MediaHelper::delete_file($base_path.'temp/', $this->nome_temp);

		}
		return parent::delete();
	}
}
