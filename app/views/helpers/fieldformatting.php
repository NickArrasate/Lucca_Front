<?php

class FieldformattingHelper extends AppHelper {

    function modify($fieldname) {
     
	   $fieldname = preg_replace('/_/',' ',$fieldname);
	   $fieldname = ucwords($fieldname);
	   return $fieldname;
	   
    }
	
	function append($key, $value, $append ='') {
	
		if ($key == 'height' || $key == 'height_2' || $key == 'width' || $key == 'depth' || $key == 'diameter') {
			$value = $value . ' ' . $append;
		}
		return $value;
	}
	
	function price_formatting($price) {

			$price = number_format($price);
			$price = (string)$price;
			$price = preg_replace('/\.00/','',$price);
			return $price;
	
	}
}

?>
