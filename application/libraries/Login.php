<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	用户登录类
*/
class Login{
	private $CI;//登录类内部操作
	var $user_info;//用户信息
	var $cookie_info;//cookie信息
	var $post_info;
	var $is_login=FALSE;
	var $redirect_to="/user";
	private $error_info;
	public function __construct(){
		$this->error_info=array();
		$this->CI=& get_instance();
	}	
	public function auto_login(){
		if($this->get_sid())
		{
			if(($this->cookie_info['sid']['time']+604800)<time() && $this->cookie_info['sid']['time']>0)return FALSE;
			if($this->sql_checking_sid()){
				$this->set_login_status();
				return TRUE;
			}else return FALSE;
		}
		else
			return FALSE;
	}
	public function post_login(){
		$this->post_info=$this->CI->input->get_post('login');
		$this->CI->db->select('id, email, password, lock');
		$s=$this->CI->db->get_where('user',array('email'=>$this->post_info['account']));
		$s=$s->first_row();
		if(empty($s)){array_push($this->error_info,'用户账号或密码不正确。');return FALSE;}
		if(!isset($this->post_info['encrypt']) || $this->post_info['encrypt']!='1')$this->post_info['passwd']=md5($this->post_info['passwd']);
		if($s->email!=$this->post_info['account'] || $s->password!=make_password($this->post_info['passwd'],TRUE)){array_push($this->error_info,'用户账号或密码不正确。');return FALSE;}
		if($s->lock=='TRUE'){array_push($this->error_info,'账号被禁止登陆。');return FALSE;}
		if(!$this->set_login_cookie($s->id)){array_push($this->error_info,'登录出现错误。');return FALSE;}
		
		delete_cookie('lg_status');//登录成功清除上次登录cookie
		
		if(!empty($this->post_info['redirect_to']) && filter_var($this->post_info['redirect_to'],FILTER_VALIDATE_URL)){
			$s=parse_url($this->post_info['redirect_to']);
			if(isset($s['host']) && $s['host']==$_SERVER['HTTP_HOST'])
				$this->redirect_to=$this->post_info['redirect_to'];
		}
		return TRUE;
	}
	public function register_login($email,$password){
		//传递md5加密后的值
		$this->post_info=array('account' => $email, 'passwd' => $password);
		$this->CI->db->select('id, email, user, password');
		$this->user_info='';
		$this->user_info=$this->CI->db->get_where('user',array('email'=>$this->post_info['account']))->first_row();
		if(empty($this->user_info))return FALSE;//无法正确获取数据，注册失败
		if($this->user_info->email!=$this->post_info['account'] || $this->user_info->password!=make_password($this->post_info['passwd'],TRUE))return FALSE;
		if(!$this->set_login_cookie($this->user_info->id))return FALSE;
		return TRUE;
	}
	public function redirect_to_login(){
		if($_SERVER['QUERY_STRING'])$page=current_url().'?'.$_SERVER['QUERY_STRING'];
		else $page=current_url();
		redirect(base_url().'login.html?redirect_to='.urlencode ($page), 'refresh');
		unset($page);
		exit;
	}
	public function logout(){
		delete_cookie('lg_sid');
		delete_cookie('lg_status');
		redirect(site_url('/login.html?loggedout=true'), 'refresh');
	}
	public function clear_login_table(){
		delete_cookie('lg_table');
		redirect(base_url('/login.html'),'refresh');
	}
	private function set_login_cookie($id=''){
		if(empty($id))return FALSE;
		if(!isset($this->post_info['time']))$this->post_info['time']='';
		$sid=$this->encrypt_sid(array('id'=>$id,'pwd'=>$this->post_info['passwd'],'time'=>$this->post_info['time']));
		if(!empty($this->post_info['time']) && $this->post_info['time']>0)
		{
			set_cookie(array('name'=>'lg_sid', 'value'=>$sid, 'expire'=>'604800'));
			set_cookie(array('name'=>'lg_table', 'value'=>$this->encrypt_table(array('email'=>$this->post_info['account'],'keep'=>'1')), 'expire'=>'2592000'));
		}
		else
		{
			set_cookie(array('name'=>'lg_sid', 'value'=>$sid, 'expire'=>'0'));
			set_cookie(array('name'=>'lg_table', 'value'=>$this->encrypt_table(array('email'=>$this->post_info['account'],'keep'=>'1')), 'expire'=>'86500'));
		}
		return TRUE;
	}
	public function get_cookie_table($string=''){
		if($string==''){
			$this->cookie_info['table']=$this->encrypt_table(get_cookie('lg_table'));
		}else if($string=='email'){
			if(isset($this->cookie_info['table']['email']))return $this->cookie_info['table']['email'];		
		}else if($string=='keep'){
			if(isset($this->cookie_info['table']['keep']))return $this->cookie_info['table']['keep'];	
		}
		return NULL;
	}
	private function get_sid(){
		if(!isset($this->cookie_info['sid']))
			$this->cookie_info['sid']=$this->encrypt_sid(get_cookie('lg_sid'));
		if(isset($this->cookie_info['sid']['id']) && isset($this->cookie_info['sid']['pwd']) && isset($this->cookie_info['sid']['time']))
			return TRUE;
		else
			return FALSE;
	}
	private function encrypt_sid($string=NULL){
		if(is_array($string)){
			//加密数据
			return $this->CI->encrypt->encode($string['id']."\t".$string['pwd']."\t".$string['time']);
		}else if(is_string($string)){
			//解密数据
			$s=explode("\t",$this->CI->encrypt->decode($string));
			$p=array('id','pwd','time');
			$t=array();
			foreach($s as $i => $v)$t[$v]=$p[$i];
			return array_flip($t);
		}else return;
		
	}
	private function encrypt_table($string=NULL){
		if(is_array($string)){
			//加密表格
			return $this->CI->encrypt->encode($string['email']."\t".$string['keep']);
		}else if(is_string($string)){
			//解密表格
			$s=explode("\t",$this->CI->encrypt->decode($string));
			$p=array('email','keep');
			$t=array();
			foreach($s as $i => $v)$t[$v]=$p[$i];
			return array_flip($t);
		}else return;
	}
	private function sql_checking_sid(){
		if($this->is_login)return TRUE;
		$this->CI->db->select('id, user, email, password,lock,active');
		$this->user_info=$this->CI->db->get_where('user',array('id'=>$this->cookie_info['sid']['id']))->first_row();
		if(empty($this->user_info) ||$this->user_info->lock=='TRUE' )return FALSE;
		if($this->user_info->password!=make_password($this->cookie_info['sid']['pwd'],TRUE))return FALSE;
		$this->is_login=TRUE;
		unset($this->user_info->password);
		return TRUE;
	}
	private function set_login_status(){
		//设置登录状态
		//获取cookie 存在 已经登录，否则为再次登录
		//存在，无需操作
		//不存在，设置cookie 更新数据库 判断是否为同一天 调用其他操作函数
		if(!get_cookie('lg_status')){
			set_cookie(array('name'=>'lg_status', 'value'=>1, 'expire'=>'0'));
			$this->CI->db->insert('user_login', array('user'=>$this->user_info->id,'ip'=>$this->CI->input->ip_address(),'ua'=>$_SERVER['HTTP_USER_AGENT']?$_SERVER['HTTP_USER_AGENT']:NULL));
		}		
	}
	public function login_errors($before='',$end=''){
		//返回错误的登录信息
		$tmp='';
		foreach($this->error_info as $v)
			$tmp.=$before.' '.$v.' '.$end."\n";
		return $tmp;
	}
}

?>