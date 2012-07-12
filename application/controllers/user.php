<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('system','encrypt','login'));
		$this->load->helper(array('cookie','url','html','form','password','template'));
	}
	public function index()
	{
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->system->set_menu_id('index');
		$data=array('title'=>'用户','meta'=>array(),'link_tag'=>array());
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/index');
		$this->load->view('user/template/footer');
	}
	public function profiles($id=NULL){
		echo $id;	
	}
	public function login()
	{
		$act=$this->input->get('act');
		if($act=='logout')$this->login->logout();
		if($act=='clear')$this->login->clear_login_table();

		if($this->input->post())
		{
			$this->logining();
		}
		else
		{
			$this->login->get_cookie_table();
			$data=array('title' => "用户登录", 'meta' => array());
			$this->load->view('user/login',$data);		
		}
	}
    public function register(){
		if($this->login->auto_login())redirect($this->login->redirect_to, 'refresh');
		
        
		$this->load->library(array('register','form_validation','session'));
		$data=array(
					'title'=>'用户注册',
					'meta'=>array(),
					'link_tag'=>array(
									array('href' => 'css/user/register.css','rel' => 'stylesheet','type' => 'text/css')
									),
					'close'=>$this->system->register_close(),
					'status'=>FALSE
				);
		
		if($this->input->post() && !$data['close'])
		{
			$this->register->get_register_info();
			$this->form_validation->set_rules('register[user]', '用户名', 'required|min_length[2]|max_length[18]');
			$this->form_validation->set_rules('register[email]', '邮箱', 'required|valid_email');
			$this->form_validation->set_rules('register[password]', '密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('register[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('register[verification]', '验证码', 'required');
			$this->form_validation->set_rules('register[agreement]', '同意网站协议', 'required');
			
			$data['title']='用户注册失败';
			if($this->form_validation->run() && $this->register->check_errors() && $this->register->inster_register_info())
			{
				$data['status']=TRUE;
				$data['title']='欢迎 '.$this->register->get('user').' - 用户注册成功';				
			}
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/register');
		$this->load->view('user/template/footer');
    }
	private function logining()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_message('required','用户 %s 不能为空');
		$this->form_validation->set_rules('login[account]', '账户', 'required');
		$this->form_validation->set_rules('login[passwd]', '密码', 'required');
		$this->form_validation->set_message('valid_email','这是一个错误的邮箱 %s');

		$this->form_validation->set_rules('login[account]', '账户名', 'valid_email');

		$data=array('title' => "用户登录出错", 'meta' => array());
		if(!$this->form_validation->run())
		{
			$this->load->view('user/login',$data);	
		}
		else
		{
			$this->form_validation->set_message('valid_email','%s 不正确');	
			$this->form_validation->set_rules('login[passwd]', '密码', 'alpha_dash|min_length[6]|max_length[32]');
			if(!$this->form_validation->run())
			{
				$this->load->view('user/login',$data);	
			}
			else
			{
				if($this->login->post_login()){
					redirect($this->login->redirect_to, 'refresh');
				}
				else
				{
					$this->load->view('user/login',$data);
				}
			}
		}
	}//logining
	
	public function safe(){
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->system->set_menu_id('safe');
		$data=array('title'=>'安全设置',
					'meta'=>array(),
					'link_tag'=>array(
									array('href' => 'css/user/safe.css',
											'rel' => 'stylesheet',
											'type' => 'text/css'
											)
									)
					);
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/safe');
		$this->load->view('user/template/footer');
	}
	public function active($act='',$code=''){
		$this->load->library(array('Mail','Safe'));
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->system->set_menu_id('safe','active');
		$data=array('title'=>'账户激活',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/safe.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							)
		);
		if($act=='send_mail')
		{
			$data['mail_sent']=$this->safe->active_mail();
		}else if($act=='V' && $code!=''){
			$data['verification']=$this->safe->verification($code);
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/active');
		$this->load->view('user/template/footer');
	}
	public function email($post=''){
		$this->load->library(array('Mail','Safe','form_validation'));
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->system->set_menu_id('safe','email');
		$data=array('title'=>'修改邮箱地址',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/safe.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false
		);
		if($post=='post'){
			//存在数据提交，检验数据
			$this->form_validation->set_rules('new_email', '新地址', 'required|valid_email');
			$this->form_validation->set_rules('v_code', '验证码', 'required|min_length[10]|max_length[10]');
			if($this->form_validation->run()){
				$data['status']=$this->safe->post_edit_email();
			}
		}else if($post=='send_mail'){
			//发送验证邮件
			$this->safe->send_edit_code();
			exit;
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/edit_email');
		$this->load->view('user/template/footer');
	}
	public function password($act=''){
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->load->library(array('Safe','form_validation','session'));
		$this->system->set_menu_id('password','password');
		$data=array('title'=>'修改登录密码',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/safe.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false
		);
		if($act=='post'){
			$this->form_validation->set_rules('password[old]', '旧密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('password[new]', '新密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('password[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('password[v]', '验证码', 'required|min_length[4]|max_length[4]');
			if($this->form_validation->run()){
				$data['status']=$this->safe->edit_password();
			}
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/edit_password');
		$this->load->view('user/template/footer');
	}
	public function PIN($act=''){
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->load->library(array('Safe','form_validation'));
		$this->system->set_menu_id('password','PIN');
		$data=array('title'=>'修改PIN密码',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/safe.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false,
			'type'=>'edit'
		);
		$this->safe->get_PIN();//获取PIN
		if($this->safe->get_PIN('PIN')=='')$data['type']='creat';
		if($act!=''){
			$this->form_validation->set_rules('pwd[new]', '新的密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('pwd[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
		}
		if($act=='creat'){
			if($this->form_validation->run())$data['status']=$this->safe->creat_PIN('PIN');
		}else if($act=='edit'){
			$this->form_validation->set_rules('pwd[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
			if($this->form_validation->run())$data['status']=$this->safe->edit_PIN('PIN');
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/edit_PIN');
		$this->load->view('user/template/footer');
	}
	public function HPIN($act=''){
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->load->library(array('Safe','form_validation'));
		$this->system->set_menu_id('password','HPIN');
		$data=array('title'=>'修改登录记录查看密码',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/safe.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false,
			'type'=>'edit'
		);
		$this->safe->get_PIN();//获取PIN
		if($this->safe->get_PIN('HPIN')=='')$data['type']='creat';
		if($act!=''){
			$this->form_validation->set_rules('pwd[new]', '新的密码', 'required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('pwd[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
		}
		if($act=='creat'){
			if($this->form_validation->run())$data['status']=$this->safe->creat_PIN('HPIN');
		}else if($act=='edit'){
			$this->form_validation->set_rules('pwd[confirm]', '确认密码', 'required|min_length[6]|max_length[32]');
			if($this->form_validation->run())$data['status']=$this->safe->edit_PIN('HPIN');
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/edit_HPIN');
		$this->load->view('user/template/footer');
	}
	public function profile($act=''){
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		$this->load->library(array('Safe','form_validation'));
		$this->system->set_menu_id('profile');
		$this->safe->get_PIN();//获取PIN
		$data=array('title'=>'个人信息',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/profile.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false
		);
		if($act=='edit_name'){
			$this->form_validation->set_rules('new_name', '用户名', 'required|min_length[2]|max_length[18]');
			if($this->form_validation->run())$data['status']=$this->safe->edit_username();
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/profile');
		$this->load->view('user/template/footer');		
	}
}
?>