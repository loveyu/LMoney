<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_history extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('system','encrypt','login'));
		$this->load->helper(array('cookie','url','html','form','password','template'));
		if(!$this->login->auto_login())$this->login->redirect_to_login();
		
		$this->load->library('history');//该类构造函数中有用到template的函数，且需要在登录之后调用
		$this->system->set_menu_id('login');
	}
	public function index(){
		if(!$this->history->auto_login())return $this->login();
		$data=array('title'=>'个人登录历史查询',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/login_history.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'status'=>false
		);
		
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('login_history/index');
		$this->load->view('user/template/footer');		
	}
	public function login($act=''){
		if($this->history->auto_login())$this->history->logout();
		$data=array('title'=>'登录历史查询中心',
			'meta'=>array(),
			'link_tag'=>array(
							array('href' => 'css/user/login_history.css',
									'rel' => 'stylesheet',
									'type' => 'text/css'
									)
							),
			'logout'=>false,
			'pwd'=>true
		);
		$data['no_pwd']=!$this->history->have_HPIN();
		if($this->history->auto_login()){
			//如果已经登录，访问登录页面表示注销登录
			$this->history->logout();
			$data['logout']=true;
		}
		if(!$data['logout'] && $act=='post'){
			$data['pwd']=$this->history->post_login();
			if($data['pwd'])die(redirect(site_url('login_history'), 'refresh'));
		}
		$this->load->view('user/template/header',$data);
		$this->load->view('user/template/menu');
		$this->load->view('login_history/login');
		$this->load->view('user/template/footer');			
	}
}