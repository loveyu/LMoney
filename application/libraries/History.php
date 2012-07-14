<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	历史记录操作
*/

class History{
	private $CI;
	private $HPIN='';
	private $is_login=FALSE;
	function __construct(){
		$this->CI=& get_instance();
		
		$this->CI->db->where('id', get_user_id());
		$this->CI->db->select(array('HPIN'));
		$this->HPIN=$this->CI->db->get('user')->row()->HPIN;
	}
	public function have_HPIN(){
		return !empty($this->HPIN);
	}
	public function is_login(){
		return $this->is_login;
	}
	public function auto_login(){
		$pwd=$this->get_HPIN_cookie();
		if(make_password($pwd,true)==$this->HPIN){
			$this->is_login=TRUE;
			return true;
		}
		return false;
	}
	public function logout(){
		delete_cookie('HPIN');
		$this->is_login=false;
	}
	public function post_login(){
		$post=$this->CI->input->get_post('HPIN');
		if(strlen($post)<6 || strlen($post)>32)return false;
		if(make_password($post)!=$this->HPIN)return false;
		$this->set_HPIN_cookie(md5($post));
		return true;
	}
	public function set_HPIN_cookie($v=''){
		set_cookie(array('name'=>'HPIN', 'value'=>$this->CI->encrypt->encode($v), 'expire'=>'0'));
	}
	
	public function get_HPIN_cookie(){
		return $this->CI->encrypt->decode(get_cookie('HPIN'));
	}

}

?>