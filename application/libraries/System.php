<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	系统设置
*/
class System{
	private $db_config;
	private $CI;
	private $err_info;
	private $menu_id;
	public function __construct(){
		$this->error_info=array();
		$this->db_config=array();
		$this->menu_id=array();
		$this->CI=& get_instance();
		$this->get_config();
	}
	private function get_config(){
		$this->CI->db->where('auto_lading','TRUE');
		$this->CI->db->select('id, name, value');
		$t=$this->CI->db->get('setting')->result_array();
		foreach($t as $v)$this->db_config[$v['name']]=$v;
		unset($v);
	}
	public function get_email_config(){
		$this->CI->db->where('name','email');
		$this->CI->db->select('value');
		$t=explode(';',$this->CI->db->get('setting')->row()->value);
		$y=array();
		foreach($t as $v){
			$n=strpos($v,':');
			$y[substr($v,0,$n)]=substr($v,$n+1);
		}
		return $y;
	}
	public function get_email_send(){
		$this->CI->db->where('name','email_send_info');
		$this->CI->db->select('value');
		$t=explode(';',$this->CI->db->get('setting')->row()->value);
		$y=array();
		foreach($t as $v){
			$n=strpos($v,':');
			$y[substr($v,0,$n)]=substr($v,$n+1);
		}
		return $y;
	}
	public function get_sitename(){
		return $this->db_config['sitename']['value'];
	}
	public function register_close(){
		return $this->db_config['register_close']['value']=='TRUE';
	}
	public function set_menu_id($s1='',$s2=''){
		if($s1!=''){
			$this->menu_id[0]=$s1;
			$this->menu_id[1]='';
		}
		if($s2!='' && isset($this->menu_id[0]) && $this->menu_id[0]!=''){
			$this->menu_id[1]=$s2;
		}
	}
	public function get_menu_id($id=0){
		if($id>=0)return $this->menu_id[$id];
		else return '';
	}
}
?>