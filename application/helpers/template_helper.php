<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('sitename'))
{
	function sitename()
	{
		return get_instance()->system->get_sitename();
	}
}

if ( ! function_exists('get_user_avata'))
{
	function get_user_avata()
	{
		if(is_login())
			return "http://1.gravatar.com/avatar/".md5(get_instance()->login->user_info->email)."?s=50";
		else return "http://1.gravatar.com/avatar/".md5("")."?s=50";
	}
}

if ( ! function_exists('get_user_name'))
{
	function get_user_name($begin='',$end='')
	{
		if(is_login())
			return $begin.get_instance()->login->user_info->user.$end;
		else return $begin."&nbsp;未"."<a href=\"".base_url("login.html?redirect=true")."\" class=\"login\">登录</a>!".$end;
	}
}

if ( ! function_exists('get_logout_link'))
{
	function get_logout_link($reg=false)
	{
		if(is_login())
			return "&nbsp;<a href=\"".site_url("login.html?act=logout")."\" class=\"logout\">注销</a>";
		else if($reg) return "&nbsp;<a href=\"".site_url("register.html")."\" class=\"register\">注册</a>";
		else return "&nbsp;<a href=\"".site_url("login.html?redirect=true")."\" class=\"login\">登录</a>";
	}
}

if ( ! function_exists('get_user_email'))
{
	function get_user_email($all=false)
	{
		$email=get_instance()->login->user_info->email;
		if($all)
			return $email;
		else
		{
			
			$a=strpos($email,'@');
			$d=strrpos($email,'.');
			$dl='';
			for($i=$a+1;$i<$d-1;$i++)$dl.='*';
			if($a/2>2)
			{
				$al='';
				for($i=$a/2+1;$i<$a;$i++)$al.='*';
				return substr($email,0,$a/2+1).$al."@".$dl.substr($email,$d-1);
			}
			else 
				return substr($email,0,$a)."@".$dl.substr($email,$d-1);
		}
	}
}

if ( ! function_exists('is_login'))
{
	function is_login()
	{
		return get_instance()->login->is_login;
	}
}

if ( ! function_exists('get_menu_active'))
{
	function get_menu_active($s='',$id=0)
	{
		if(get_instance()->system->get_menu_id($id)==$s)echo ' class="active"';
	}
}



?>