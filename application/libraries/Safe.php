<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	安全设置
*/

class Safe{
	private $CI;
	public $error;
	function __construct(){
		$this->CI=& get_instance();
		$this->error=array();
	}
	public function active_mail(){
		if(get_user_active()){
			array_push($this->error,'账户已经激活，不需要重新激活');
			return FALSE;
		}
		else return $this->CI->mail->send_active_mail(array('user'=>get_user_name(),'email'=>get_user_email(true),'key'=>$this->get_new_active_key(get_user_id())));
	} 
	private function get_new_active_key($id){
		$new_key=$this->rand_new_key();
		$this->CI->db->where('id', $id);
		$this->CI->db->update('user', array('active'=>'FALSE'));
		$this->CI->db->where('id', $id);
		$this->CI->db->update('aq', array('active_code'=>$new_key,'active_time'=>date('Y-m-d H:i:s')));
		return $new_key;
	}
	public function verification($code=''){
		if(get_user_active()){
			array_push($this->error,'账户已经激活，不需要重新激活');
			return FALSE;
		}
		$this->CI->db->where('id', get_user_id());
		$this->CI->db->select(array('active_code','active_time'));
		$s=$this->CI->db->get('aq')->row();
		if( $s->active_time=='' || strtotime($s->active_time)+172800<time()){
			array_push($this->error,'激活码超出有效期，请重新激活');
			return FALSE;
		}
		if($code!=$s->active_code)return FALSE;
		else
		{
			$this->CI->db->where('id', get_user_id());
			$this->CI->db->update('user', array('active'=>'TRUE'));	
			$this->CI->db->where('id', get_user_id());
			$this->CI->db->update('aq', array('active_code'=>'','active_time'=>date('Y-m-d H:i:s')));
			return TRUE;
		}
	}
	private function rand_new_key($length=32){
		$pattern='0123456789qwertyuiopasdfghjklzxcvbnmMNBVCXZLKJHGFDSAPOIUYTREWQ';
		$key=null;
		$len=strlen($pattern);
		for($i=0;$i<$length;$i++)
			$key.=$pattern{mt_rand(0,$len-1)};
		return $key;
	}
}