<?php

namespace App\Rules;

use App\Http\Traits\NumbersOnly;
use Illuminate\Contracts\Validation\Rule;

class DocumentoValid implements Rule
{

	use NumbersOnly;

	private $black_list = [
		"00000000000000","11111111111111","22222222222222","33333333333333","44444444444444",
		"55555555555555","66666666666666","77777777777777","88888888888888","99999999999999",
		"00000000000","11111111111","22222222222","33333333333","44444444444",
		"55555555555","66666666666","77777777777","88888888888","99999999999"
	];
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
    	$value = $this->getOnlyNumbers($value);
    	$size_string = strlen($value);
    	if($size_string === 14)
    		$positions = array(12,13);
    	elseif ($size_string == 11)
		    $positions = array(9,10);
    	else
		    return false;
        if(in_array($value,$this->black_list))
        	return false;
        foreach ($positions as $position){
	        $digit = $this->getProveNumber(substr($value,0,$position));
	        if($digit != $value[$position])
		        return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Documento inválido.';
    }

	/**
	 * @param $string
	 *
	 * @return int
	 */
	private function getProveNumber($string){
    	$length = strlen($string);
	    $weight = 2;
	    $sum = 0;
	    for ($i = ($length - 1); $i >= 0;$i--){
		    $sum += $string[$i] * $weight;
		    $weight++;
		    if($weight > 9 && $length > 10)
			    $weight = 2;
	    }
	    $module = $sum % 11;
		$digit = $module >= 2 ? 11 - $module : 0;
	    return $digit;
    }

	/**
	 * Convert the rule to a validation string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return "documentovalid";
	}
}
