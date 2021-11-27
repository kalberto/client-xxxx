<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/11/2017
 * Time: 18:19
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/11/2017
 * Time: 18:19
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaRoot extends Model
{
	use SoftDeletes;

	protected $table = 'media_root';
	protected $fillable = ['alias','path'];
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function medias(){
		return $this->hasMany('App\Media');
	}
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function media_resizes(){
		return $this->hasMany('App\MediaResize');
	}
}
