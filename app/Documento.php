<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 21/03/2018
 * Time: 15:10
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 17/01/2020
 * Time: 17:00
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;

class Documento extends Model
{
	use SearchableTrait;

	protected $table = 'documento';
	protected $fillable = ['documento','organizacao_id'];
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function organizacao(){
		return $this->belongsTo('App\Organizacao');
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data,$ip){
		$faq = new self;
		$faq->fill($data);
		if($faq->save()){
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $faq->id,
				'tabela' => 'documento',
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
		$faq = self::find($id);
		$faq->fill($data);
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $faq->id,
			'tabela' => 'documento',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $faq->save();
	}
}
