<?php

namespace App\Helpers;

class VariableHelper {
	public static function convert_string_bool(&$value){
		if($value == "true")
			$value = true;
		elseif($value == "1")
			$value =  true;
		elseif($value === true)
			$value = true;
		elseif($value === 1)
			$value = true;
		else
			$value = false;
	}

	public static function convertDateFormat(&$date, $old_format, $new_format){
		if(isset($date)){
			$data = \DateTime::createFromFormat( $old_format,$date);
			if($data != false)
				$date = $data->format($new_format);
			else
				$date = false;
		}
	}
}