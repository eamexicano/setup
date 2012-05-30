<?php
// Cambia la palabra a su plural en español.
function pluralize($string)	{
	if (preg_match("/[aeiou]+$/i", $string)) {
		$string .= "s";
	} elseif (preg_match("/[rlndszjx]+$/i", $string)) {
		$string .= "es";
	} elseif (preg_match("/[aeiou]y+$/i", $string)) {
		$string .= "es";
	} elseif (preg_match("/[^rlndszjx]+$/i", $string)) {
		$string .= "s";
	};
  	return $string;
}
?>