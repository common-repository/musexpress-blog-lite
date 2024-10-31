<?php


class Musexpress_blog_lite_utility{




	public static function get_string_between( $string, $start, $end ) {
		$string = ' ' . $string;
		$ini    = strpos( $string, $start );
		if ( $ini == 0 ) {
			return '';
		}
		if(strpos($string,$end)===false){
			return '';
		}
		$ini += strlen( $start );
		$len = strpos( $string, $end, $ini ) - $ini;

		return substr( $string, $ini, $len );
	}

}