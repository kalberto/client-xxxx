<?php

namespace App\Http\Requests\VideosSite;

class EditRequest extends VideosSiteRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required','exists:videos_site,id'];
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
		return $data;
	}
}
