<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	邮件模块
*/
class Mail{
	private $CI;
	private $mail;
	private $send;
	function __construct(){
		$this->CI=& get_instance();
		$mail_config=$this->CI->system->get_email_config();
		$this->send=$this->CI->system->get_email_send();
		require_once('class.phpmailer.php');
		$this->mail = new PHPMailer(true);
		$this->set_config($mail_config);
		$this->mail->SMTPDebug  = 1;
		$this->mail->CharSet = "UTF-8";
	}
	public function send_active_mail($s=array('user'=>'','email'=>'','key'=>'')){
		$flag=false;
		try {
			$this->mail->AddReplyTo($this->send['from'],$this->send['name']);
			$this->mail->AddAddress($s['email'],$s['user']);
			$this->mail->SetFrom($this->send['from'],$this->send['name']);
			$this->mail->Subject = '邮箱账户激活 - '.$this->CI->system->get_sitename();
			$this->mail->MsgHTML('
<html>
<div style="background-color:#ACEFFD;padding:20px;">
<p>'.$s['user'].' 你好:</p>
<p>请点击此链接激活你的账户:<a href="'.site_url('user/active/V/'.$s['key']).'" target="_blank">'.site_url('user/active/V/'.$s['key']).'</a></p>
<p>请在48小时内有效</p>
</div>
</html>
			');
			if($this->mail->Send())$flag=true;
		} catch (phpmailerException $e) {
			//echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			//echo $e->getMessage(); //Boring error messages from anything else!
		}
		return $flag;
	}
	private function set_config($config){
		foreach($config as $n => $v){
			switch($n){
				case 'protocol':
					if($v=='smtp')$this->mail->IsSMTP();
				break;
				case 'smtp_host':
					if($v!='')$this->mail->Host=$v;
				break;
				case 'smtp_user':
					if($v!='')$this->mail->Username=$v;
				break;
				case 'smtp_pass':
					if($v!='')$this->mail->Password=$v;
				break;
				case 'smtp_port':
					if($v!='')$this->mail->Port=$v;
				break;
				case 'smtp_auth':
					if($v=='true' || $v=='TRUE')$this->mail->SMTPAuth= true; 
				break;
			}
		}
	}
}
?>