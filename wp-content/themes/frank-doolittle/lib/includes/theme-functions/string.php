<?php

/**
 * Add http:// if missing
 */
function addhttp($url) {
    if (false === strpos($url, '://')) {
		$url = 'http://' . $url;
	}
    return $url;
}

function reverse_words( $str )
{
	$myArray = str_word_count($str, 1);
	$reverse = array_reverse($myArray);
	return 	implode( ' ', $reverse );
}


function dq_to_sq( $string ) {
    return str_replace( '"', "'", $string );   
}

function format_money( $value ) {
    return sprintf( '<sup>$</sup>%s', $value );
    // return sprintf( '<sup>$</sup>%s', number_format( $value, 2, '.', ',' ) );
}