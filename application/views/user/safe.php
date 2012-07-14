<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id="check_index">
<?php
$i=0; 
if(!get_user_active()){
	echo "<p>请激活你的邮箱账户!(<strong>非常重要</strong>)</p>\n";
	$i++;
}
if(!$this->safe->get_PIN('PIN')){
	echo "<p>请设置机密PIN密码!</p>\n";
	$i++;	
}
if(!$this->safe->get_PIN('HPIN')){
	echo "<p>请设置登录记录查看密码!</p>\n";
	$i++;	
}
if($i==0)echo "<p>你的安全状态良好</p>\n";
?>
</div>

</div><!-- safe-page -->
</div><!-- safe-->
<div class="clear"></div>

