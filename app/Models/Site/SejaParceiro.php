<?php

namespace App\Models\Site;

use App\Helpers\VariableHelper;
use App\Http\Traits\ModelLogTrait;
use App\Models\Site\Translations\VideosTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static SejaParceiro find($id)
 * @method static Builder where(string|array $column,mixed $operator = null,mixed $value = null,string $boolean = 'and')
 **/
class SejaParceiro extends Model
{
	use ModelLogTrait;

	protected $table = 'seja_parceiro';
	protected $fillable = ['documento','data'];

	public function setDataAttribute($value){
		$this->attributes['data'] = json_encode($value);
	}

	public function getDataAttribute(){
		return json_decode($this->getOriginal('data'));
	}


	public static function store($data,$ip) {
		$registro = new self;
		$registro->fill($data);
		if($registro->save()){
			$registro->saveLog($ip,'insert',$data);
			return $registro;
		}
		return false;
	}

	public static function edit($id,$data,$ip){
		$registro = self::find($id);
		$registro->fill($data);
		if($registro->save()){
			$registro->saveLog($ip,'update',$data);
			return $registro;
		}
		return false;
	}

	public function remove($ip){
		$this->deleteLog($ip);
		return $this->delete();
	}
}
