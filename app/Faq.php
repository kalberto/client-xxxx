<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/02/2017
 * Time: 15:12
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;

class Faq extends Model
{
	use SoftDeletes;
	use SearchableTrait;

	protected $table = 'faq';
	protected $fillable = ['pergunta','resposta','ativo','tipo_servico','tipo_servico_url'];
	protected $hidden = [];
	protected $searchable = [
		'columns' => [
			'faq.pergunta' => 10,
		]
	];

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data,$ip) {
		$faq = new self;
		$faq->fill($data);
		if($faq->save()){
			$data = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $faq->id,
				'tabela' => 'faq',
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
			'tabela' => 'faq',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $faq->save();
	}

	public static function getByServico($url){
		$faqs = Faq::where('tipo_servico_url' ,$url)->orderBy('pergunta')->paginate(20);
		return $faqs;
	}

	public static function getByServicoName($name){
		$faqs = Faq::where('tipo_servico' ,$name)->orderBy('pergunta')->paginate(20);
		return $faqs;
	}

	public static function getByServicos($url){
		$faqs = Faq::whereIn('tipo_servico_url' ,$url)->orderBy('pergunta');
		return $faqs;
	}

	public static function getByServicosName($name){
		$faqs = Faq::whereIn('tipo_servico' ,$name)->orderBy('pergunta');
		return $faqs;
	}

	public static function countTotal(){
		return self::count();
	}
}
