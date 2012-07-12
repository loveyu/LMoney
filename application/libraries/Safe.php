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
	public function send_edit_code(){
		$code=$this->rand_new_key(10);
		$this->CI->db->where('id', get_user_id());
		$this->CI->db->update('aq', array('edit_code'=>$code,'edit_time'=>date('Y-m-d H:i:s')));
		$this->CI->mail->send_edit_mail(array('user'=>get_user_name(),'email'=>get_user_email(true),'key'=>$code));
	}
	public function post_edit_email(){
		$email=$this->CI->input->get_post('new_email');
		$code=$this->CI->input->get_post('v_code');
		$this->CI->db->where('id', get_user_id());
		$this->CI->db->select(array('edit_code','edit_time'));
		$s=$this->CI->db->get('aq')->row();
		if( $s->edit_time=='' || strtotime($s->edit_time)+7200<time())
			array_push($this->error,'验证码超出有效期，请重发激活码');
		if($s->edit_code=='' || $s->edit_code!=$code)
			array_push($this->error,'验证码错误，请重发激活码');
		if($email==get_user_email(true))
			array_push($this->error,"不能使用相同的邮箱");
		if(count($this->error)==0){
			$this->CI->db->where('id', get_user_id());//删除验证码
			$this->CI->db->update('aq', array('edit_code'=>'','edit_time'=>date('Y-m-d H:i:s')));
			$this->CI->db->where('id', get_user_id());//取消激活
			$this->CI->db->update('user', array('active'=>'FALSE','email'=>$email));
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function edit_password(){
		$post=$this->CI->input->get_post('password');
		if($this->CI->session->userdata('verification_code')!=$post['v']){
			$this->CI->session->unset_userdata('verification_code');
			array_push($this->error,"验证码错误");
			return FALSE;
		}
		if($post['old']==$post['new']){
			array_push($this->error,"两次密码一样，无需修改");
			return FALSE;
		}
		if(md5($post['old'])!=$this->CI->login->cookie_info['sid']['pwd'])
			array_push($this->error,"密码不正确");
		
		if($post['new']!=$post['confirm'])
			array_push($this->error,'两次密码输入不一致');
		
		if(count($this->error)>0)return FALSE;
		
		$this->CI->db->where('id', get_user_id());
		$this->CI->db->update('user', array('password'=>make_password($post['new'])));
		$this->CI->login->register_login(get_user_email(true),md5($post['new']));		
		return TRUE;
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
	public function safe_errors($before='',$end=''){
		//返回错误的登录信息
		$tmp='';
		foreach($this->error as $v)
			$tmp.=$before.' '.$v.' '.$end."\n";
		return $tmp;
	}
}