<div id="forget_account">
	<h2><a href="<?php echo site_url("/forget_account.html");?>">找回密码</a></h2>
<?php
switch($this->input->get('next')){
	case '':
	case '1':
?>
	
	<form action="<?php echo site_url("/forget_account.html");?>" method="post">
		<table width="100%" border="1" align="center" cellspacing="0">
<?php
	echo validation_errors("\t\t\t".'<tr><td colspan="3" class="error">', '</td></tr>');
	echo $this->forgetaccount->errors("\t\t\t".'<tr><td colspan="3" class="error">', '</td></tr>');
	if($send)echo "\t\t\t",'<tr><td colspan="3" class="succ">邮件已发送</td></tr>';
?>
			<tr><th>邮箱</th><td colspan="2"><input name="email" type="text" value="<?php echo set_value('email');?>"></td></tr>
			<tr><th>验证码</th><td><input name="v" type="text"></td><td><img src="<?php echo site_url('func/verification_code');?>" align="absmiddle" onclick="this.src='<?php echo base_url('func/verification_code');?>?'+Math.random();" title="点击刷新"></td></tr>
			<tr><td colspan="3" align="center"><button type="submit">找回密码</button></td></tr>
		</table>
	</form>
<?php
	break;
	
	case '2':
	if(!$status){
?>
	<form action="<?php echo site_url("/forget_account.html?next=2&mail=".$this->input->get('mail')."&key=".$this->input->get('key'))?>" method="post">
		<table width="100%" border="1" align="center" cellspacing="0">
<?php
	echo validation_errors("\t\t\t".'<tr><td colspan="2" class="error">', '</td></tr>');
	echo $this->forgetaccount->errors("\t\t\t".'<tr><td colspan="2" class="error">', '</td></tr>');
?>
			<tr><th>新的密码</th><td><input name="new" type="password"></td></tr>
			<tr><th>确认密码</th><td><input name="confirm" type="password"></td></tr>
			<tr><td colspan="2" align="center"><button type="submit">修改</button></td></tr>
			<input name="mail" value="<?php echo $this->input->get('mail')?>" type="hidden">
			<input name="key" value="<?php echo $this->input->get('key')?>" type="hidden">
		</table>
	</form>
<?php
	}else{
?>
	<p class="succ_find">密码找回成功</p>
<?php	
	}
	break;
}
?>
</div>
