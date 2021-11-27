<?php

namespace App\Http\Requests\Paginas;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EditRequest extends PaginasRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required','exists:paginas,id'];
		return $rules;
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		$this->forceUrl($data);
		$this->uniqueUrlRule($data['id']);
		return $data;
	}
}
