<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('sitename'))
{
	function sitename()
	{
		return get_instance()->system->get_sitename();
	}
}

?>