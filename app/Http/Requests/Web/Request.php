<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request as BaseRequest;

class Request extends BaseRequest
{
	public function rules() {
		return [
			'language' => 'exists:languages,locale'
		];
	}
}

