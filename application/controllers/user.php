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
			$this->form_validation->set_rules('register[user]', '用户名', 'required|min_length[4]|max_length[18]');
			$this->form_validation->set_rules('register[email]', '邮箱', 'required|valid_email');
			$this->form_validation->set_rules('register[password]', '密码', 'required|min_length[6]|max_length[16]');
			$this->form_validation->set_rules('register[confirm]', '确认密码', 'required|min_length[6]|max_length[16]');
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
			$this->form_validation->set_rules('login[passwd]', '密码', 'alpha_dash|min_length[6]|max_length[16]');
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
	public function active(){
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
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('user/active');
		$this->load->view('user/template/footer');
	}	
}
?>