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

class Configuracao extends Model
{
	use SoftDeletes;

	protected $table = 'configuracao';
	public $timestamps  = false;
	protected $fillable = ['nome_app','seo_sufix','tag_manager_id','email_remetente','email_destinatario','telefone','whatsaspp',
		'social_facebook','social_twitter','social_instagram','social_youtube','local_latitude','local_longitude','local_cep',
		'local_endereco','local_numero','local_complemento','local_bairro','local_cidade','local_estado','local_pais'];
	protected $hidden = [];

	/**
	 * @param $data array
	 *
	 * @return boolean
	 */
	public  function edit($data){
		$this->fill($data);
		return $this->save();
	}
}
