<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('make_password'))
{
	function make_password($string,$md5=false)
	{
		$string=($md5?$string:md5($string));
		if(strlen($string)!=32)$string=md5($string);
		$string=md5(substr($string,0,11)).sha1(substr($string,11));
		return sha1($string);
	}
}

?>