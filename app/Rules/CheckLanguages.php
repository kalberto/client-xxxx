<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckLanguages implements Rule
{
	private $customMessage = "A linguagem não existe";
	public function __construct() {
	}

	/**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
    	$array = explode('.',$attribute);
    	if(isset($array[1])){
    		if(DB::table('languages')->where('locale','=',$array[1])->count() > 0){
    			return true;
		    }
		    $this->customMessage = "A linguagem $array[1] não existe";
	    }

	    return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->customMessage;
    }
}
