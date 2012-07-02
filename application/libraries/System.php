<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	系统设置
*/
class System{
	private $db_config;
	private $CI;
	private $err_info;
	public function __construct(){
		$this->error_info=array();
		$this->db_config=array();
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
	public function get_sitename(){
		return $this->db_config['sitename']['value'];
	}
	public function register_close(){
		return $this->db_config['register_close']['value']=='TRUE';
	}
}
?>