<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	找回密码
*/

class ForgetAccount{
	public $error;
	private $CI;
	public $info;
	public function __construct(){
		$this->CI = &get_instance();
		$this->error = array();
	}
	public function check_post(){
		$flag = true;
		if(!$this->check_verification($this->CI->input->get_post('v'))){
			array_push($this->error,"验证码错误");
			$flag = false;
		}
		if(!$this->check_email($this->CI->input->get_post('email'))){
			array_push($this->error,"邮箱不存在");
			$flag = false;
		}
		return $flag;
	}
	public function check_get(){
		if(!$this->check_email($this->CI->input->get('mail'))){
			array_push($this->error,"邮箱不存在");
			return false;
		}
		$this->get_user_info($this->CI->input->get('mail'));
		$this->CI->db->where('id',$this->info->id);
		$this->CI->db->select(array('rest_password_code','rest_password_time'));
		$s=$this->CI->db->get('aq')->row();
		if( $s->rest_password_time=='' || strtotime($s->rest_password_time)+48*60*60<time()){
			array_push($this->error,'激活码超出有效期，请重新激活');
			return FALSE;
		}
		if($s->rest_password_code != $this->CI->input->get('key')){
			array_push($this->error,'激活码无效');
			return FALSE;
		}
		return true;
	}
	public function check_new_password(){
		if($this->CI->input->post('new')!=$this->CI->input->post('confirm')){
			array_push($this->error,'两次密码不一致');
			return FALSE;
		}
		return true;
	}
	public function send_mail(){
		$this->get_user_info($this->CI->input->get_post('email'));
		$status = false;
		$status = $this->CI->mail->send_forget_account_mail(array('user' => $this->info->user, 'email' => $this->info->email, 'key' => $this->get_key()));
		if(!$status){
			array_push($this->error,"发送邮件找回密码失败，请联系管理员");
			return false;
		}
		return $status;
	}
	public function new_password(){
		$password = make_password($this->CI->input->get_post("new"));
		$this->CI->db->where('id', $this->info->id);
		$this->CI->db->update('aq', array('rest_password_code' => '', 'rest_password_time' => date('Y-m-d H:i:s')));
		$this->CI->db->where('id', $this->info->id);
		$this->CI->db->update('user', array('password' => $password));
		if(0){
			array_push($this->error,"修改密码失败，请联系管理员");
			return false;
		}
		return true;
	}
	private function get_key(){
		$key = $this->rand_new_key(32);
		$this->up_sql($key);
		return $key;
	}
	private function get_user_info($email){
		$this->CI->db->select('id, user, email');
		$this->CI->db->where('email', $email); 
		$query = $this->CI->db->get('user')->result();
		$this->info = $query[0];
	}
	private function check_email($value){
		$this->CI->db->where('email', $value); 
		$this->CI->db->from('user');
		if($this->CI->db->count_all_results()>0)
			return true;
		else
			return false;
	}
	private function check_verification($code){
		//检测验证码
		if($this->CI->session->userdata('verification_code')==$code){
			$this->CI->session->unset_userdata('verification_code');
			return TRUE;
		}
		return FALSE;
	}
	public function errors($before='',$end=''){
		$tmp='';
		foreach($this->error as $v)
			$tmp.=$before.' '.$v.' '.$end."\n";
		return $tmp;
	}
	private function rand_new_key($length=32){
		$pattern='0123456789qwertyuiopasdfghjklzxcvbnmMNBVCXZLKJHGFDSAPOIUYTREWQ';
		$key=NULL;
		$len=strlen($pattern);
		for($i=0;$i<$length;$i++){
			$key.=$pattern{mt_rand(0,$len-1)};
		}
		return $key;
	}
	private function up_sql($key){
		$this->CI->db->where('id',$this->info->id);
		$this->CI->db->update('aq', array('rest_password_code'=>$key,'rest_password_time'=>date('Y-m-d H:i:s')));
	}
}
?>