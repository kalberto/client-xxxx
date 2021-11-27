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

class MediaResize extends Model
{
	use SoftDeletes;

	protected $table = 'media_resize';
	protected $fillable = ['width','height','path','action','media_root'];
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media_roots(){
		return $this->belongsTo('App\MediaRoot');
	}
}