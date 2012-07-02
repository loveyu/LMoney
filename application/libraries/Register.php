<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	用户注册类
*/
class Register{
	private $post_info;
	private $CI;
	private $error_info;
	var $status=FALSE;
	public function __construct(){
		$this->CI=& get_instance();
		$this->error_info=array();
	}
	public function get($v){
		if(!isset($this->post_info[$v]))return NULL;
		else return $this->post_info[$v];
	}
	public function get_register_info(){
		$this->post_info=$this->CI->input->get_post('register');

	}
	public function check_errors(){
		if(!$this->check_verification()){
			array_push($this->error_info,'<strong>验证码</strong> 错误。');
			return FALSE;
		}
		if(!$this->check_password())array_push($this->error_info,'两次密码不匹配。');
		if(!$this->check_email())array_push($this->error_info,'该邮箱已经被使用。');
		
		if(empty($this->error_info))return TRUE;
		return FALSE;
	}
	public function register_errors($before='',$end=''){
		//返回错误的注册信息
		$tmp='';
		foreach($this->error_info as $v)
			$tmp.=$before.' '.$v.' '.$end."\n";
		return $tmp;
	}
	private function check_verification(){
		//检测验证码
		if($this->CI->session->userdata('verification_code')==$this->post_info['verification']){
			$this->CI->session->unset_userdata('verification_code');
			return TRUE;
		}
		return FALSE;
	}
	private function check_email(){
		//检测email
		$this->CI->db->from('user');
		$this->CI->db->where('email',$this->post_info['email']);
		if($this->CI->db->count_all_results()>0)return FALSE;
		return TRUE;
	}
	private function check_password(){
		//密码判断
		if($this->post_info['password']!=$this->post_info['confirm'])return FALSE;
		return TRUE;
	}
	public function inster_register_info(){
		//插入注册信息
		$data=array(
			'user' => $this->post_info['user'],
			'email' => $this->post_info['email'],
			'password' => make_password($this->post_info['password'],FALSE)
			);
		$this->CI->db->insert('user', $data);
		if($this->register_login())return TRUE;
		else return FALSE;
	}
	private function register_login(){
		if($this->CI->login->register_login($this->post_info['email'],md5($this->post_info['password']))){
			$this->CI->db->insert('aq', array('id'=>$this->CI->login->user_info->id,'register_time'=>date('Y-m-d H:i:s'),'register_ip'=>$this->CI->input->ip_address()));
			return TRUE;
		}
		array_push($this->error_info,'用户注册失败，请联系管理员');
		return FALSE;
	}
}
?>