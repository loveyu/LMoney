<?=doctype('html5');?>

<html>

<head>
<?=meta(array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'));?>
<title><?=$title,' - ',sitename();;?></title>
<?=meta($meta);?>
<?=link_tag(array('href' => 'css/login.css','rel' => 'stylesheet','type' => 'text/css'));?>

</head>

<body>

<div id="title">

<h1><a href="<?=site_url()?>"><?=sitename();?></a></h1>

</div>

<div id="login">

<?php
if($this->login->auto_login())echo '<div class="loggeout">注意：您当前已经登录，再次登录会注销本次登录</div>';
echo validation_errors('<div class="login_error">', '</div>'); 
echo $this->login->login_errors('<div class="login_error">', '</div>');
if($this->input->get('loggedout')=='true')echo '<div class="loggeout">您已经登出</div>';

?>
<?php echo form_open('/login.html', array('id' => 'login_table', 'class' => 'login', 'name'=>'login', 'onsubmit'=>'return v_from();'));?>

	<div class="login_box">
		<table width="100%" border="0" cellpadding="0" cellspacing="8">
			<tr>
				<th><?=form_label('帐号','account')?></th>
				<td><?=form_input(array('name'=>'login[account]', 'id' => 'account', 'class' => 'text', 'value' => (set_value('login[account]'))?set_value('login[account]'):($this->login->get_cookie_table('email')?$this->login->get_cookie_table('email'):NULL)))?></td>
			</tr>
			<tr>
				<th><?=form_label('密码','passwd')?></th>
				<td><?=form_password(array('name'=>'login[passwd]', 'id' => 'passwd', 'class' => 'text', 'autocomplete' => "off" ,'value' => ''))?></td>
			</tr>
		</table>
	</div>
	<div class="login_status">
		<?=form_checkbox(array('name' => 'login[time]', 'id' => 'autologin', 'value' => time(), 'checked' => ((is_array($tmp=$this->input->get_post('login'))) && isset($tmp['time']) && $tmp['time']>0 )?TRUE:($this->login->get_cookie_table('keep')?TRUE:FALSE)))?>
		
		<?=form_label('保持我的登录状态','autologin')?>
		
		<a href="<?php echo base_url("register.html");?>" class="button">注册</a>
		<a href="<?php echo base_url("forget_account.html");?>">忘记密码</a>
		<div style="clear:both;"></div>
	</div>
	<div class="login_button">
		<?=form_button(array('content' => '登录', 'type' => 'submit' ))?>
		<?=form_hidden('login[redirect_to]',($this->input->get('redirect')=='true')?$_SERVER['HTTP_REFERER']:urldecode($this->input->get('redirect_to')))?>
		<div style="clear:both;"></div>
	</div>

</form>
<script language="javascript">
<!--
	function v_from(){
		var err='';
		var user=document.getElementById('account').value;
		var pwd=document.getElementById('passwd').value;
		if(user=='')err+='用户邮箱不能为空\n';
		else if(!isEmail(user))err+='请填写正确的邮箱\n';
		if(pwd.length<6 || pwd.length>32)err+='密码长度不符\n';
		if(err.length>0)
		{
			alert(err);
			return false;
		}else{
			return true;
		}
	}
	function isEmail(strEmail) {
		if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
			return true;
		else return false;
	}
-->
</script>

<div id="help">
<?php if($this->login->get_cookie_table('email')){?><a href="?act=clear" title="清除登录历史记录">清除登录记录</a><?php }?>
<a href="<?=site_url('/fqa.html#login')?>" target="_blank" title="查看帮助文件">帮助</a>
</div>

</div>
</body>
</html>
