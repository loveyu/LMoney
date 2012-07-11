<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class func extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function verification_code(){
		$this->load->library('session');
		
		$num="";
		for($i=0;$i<4;$i++){
		$num .= rand(0,9);
		}
		//4位验证码也可以用rand(1000,9999)直接生成
		//将生成的验证码写入session，备验证页面使用
		
		$this->session->set_userdata(array('verification_code' => $num));
		
		//创建图片，定义颜色值
		Header("Content-type: image/PNG");
		srand((double)microtime()*1000000);
		$im = imagecreate(60,20);
		$black = ImageColorAllocate($im, 0,0,0);
		$gray = ImageColorAllocate($im, 200,200,200);
		imagefill($im,0,0,$gray);
		
		//随机绘制两条虚线，起干扰作用
		$style = array($black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray);
		imagesetstyle($im, $style);
		$y1=rand(0,20);
		$y2=rand(0,20);
		$y3=rand(0,20);
		$y4=rand(0,20);
		imageline($im, 0, $y1, 60, $y3, IMG_COLOR_STYLED);
		imageline($im, 0, $y2, 60, $y4, IMG_COLOR_STYLED);
		
		//在画布上随机生成大量黑点，起干扰作用;
		for($i=0;$i<80;$i++)
		{
		imagesetpixel($im, rand(0,60), rand(0,20), $black);
		}
		//将四个数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
		$strx=rand(3,8);
		for($i=0;$i<4;$i++){
		$strpos=rand(1,6);
		imagestring($im,5,$strx,$strpos, substr($num,$i,1), $black);
		$strx+=rand(8,12);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}
	public function CheckAccount($type=''){
		$this->load->database();
		$value=$this->input->get('v');
		switch($type){
			case 'email':
				$this->db->where('email', $value); 
				$this->db->from('user');
				if($this->db->count_all_results()==0)die('TRUE');
				else die('FALSE');
			break;
		}
		die('FALSE');
	}
}
?>