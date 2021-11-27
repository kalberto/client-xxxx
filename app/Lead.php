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

class Lead extends Model
{
	protected $table = 'lead';
	protected $fillable = ['empresa','contato','email','telefone','assunto','mensagem','origem','form_origem','cep','endereco','numero'];
	protected $hidden = [];

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data) {
		$lead = new self;
		$lead->fill($data);
		return $lead->save();
	}

	public static function countTotal(){
		return self::count();
	}
}
