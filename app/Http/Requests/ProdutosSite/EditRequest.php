<?php

namespace App\Http\Requests\ProdutosSite;

class EditRequest extends ProdutosSiteRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required','exists:produtos_site,id'];
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
		$this->checkExterno($data);
		$this->forceUrl($data);
		$this->uniqueUrlRule($data['id']);
		return $data;
	}
}
