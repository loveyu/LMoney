<div id="register">

<?php
if(isset($_GET['close']))$close=TRUE;
if(isset($_GET['status']))$status=TRUE;
?>
<?php if(!$close){
if(!$status){
?>
<h2>用户注册</h2>
<form action="register.html" method="post" autocomplete="off"  onsubmit="return V_register();">
<?php
echo validation_errors('<p class="register_error">', '</p>');
echo $this->register->register_errors('<p class="register_error">', '</p>');
?>
<table>
<tbody>
<tr><th><label for="register_user">用&nbsp;户&nbsp;名</label></th><td><input name="register[user]" id="register_user" value="<?=set_value('register[user]')?>" type="text" onBlur="V_user();"/></td><td id="user_notice" class="err_notice"></td></tr>
<tr><th><label for="register_email">邮&nbsp;&nbsp;&nbsp;&nbsp;箱</label></th><td><input name="register[email]" id="register_email" value="<?=set_value('register[email]')?>" type="text" onblur="V_email();"/></td><td id="email_notice" class="err_notice"></td></tr>
<tr><th><label for="register_password">密&nbsp;&nbsp;&nbsp;&nbsp;码</label></th><td><input name="register[password]" id="register_password" type="password" onBlur="V_pwd();" /></td><td id="pwd_notice" class="err_notice"></td></tr>
<tr><th><label for="register_confirm">确认密码</label></th><td><input id="register_confirm" name="register[confirm]" type="password" onBlur="V_pwd_c();"/></td><td id="pwd_c_notice" class="err_notice"></td></tr>
<tr><th><label for="register_verification">验&nbsp;证&nbsp;码</label></th><td><input name="register[verification]" id="register_verification" type="text" /></td><td><img src="<?php echo site_url('func/verification_code');?>" align="absmiddle" onclick="this.src='<?php echo base_url('func/verification_code');?>?'+Math.random();" title="点击刷新"></td></tr>
</tbody>
</table>

<p class="agreement"><input type="checkbox" id="register_agreement" name="register[agreement]" <?php echo set_checkbox('register[agreement]', '1');?> value="1" /><label for="register_agreement">同意网站注册协议</label></p>
<button type="submit" class="submit">注册</button>
<script language="javascript">
<!--
	var xmlHttp;
	function V_email(){
		var email=document.getElementById('register_email').value;
		document.getElementById('email_notice').innerHTML='';
		if( isEmail(email) == false)
		{
			document.getElementById('email_notice').innerHTML='邮箱错误';
		}
		else
		{
			var url="<?php echo site_url('func/CheckAccount/email?v=');?>"+email;
			loadXMLDoc(url);
		}
	}
	function V_user(){
		user=document.getElementById('register_user').value;
		var v='';
		if(user.length==0)v='不能为空';
		else if(user.length<2)v='长度不能小于2';
		else if(user.length>18)v='长度不能大于18';
		document.getElementById('user_notice').innerHTML=v;
		
	}
	function V_pwd(){
		pwd=document.getElementById('register_password').value;
		var v='';
		if(pwd.length==0)v='不能为空';
		else if(pwd.length<6)v='长度不能小于6';
		else if(pwd.length>32)v='长度不能大于32';
		document.getElementById('pwd_notice').innerHTML=v;
		V_pwd_c();
	}
	function V_pwd_c(){
		pwd=document.getElementById('register_password').value;
		pwd_c=document.getElementById('register_confirm').value;
		var v='';
		if(pwd!=pwd_c && pwd_c!='')v='两次密码不一致';
		document.getElementById('pwd_c_notice').innerHTML=v;
	}
	function V_register(){
		var user,email,pwd,pwd_c,v,agr;
		var err='';
		user=document.getElementById('register_user').value;
		email=document.getElementById('register_email').value;
		pwd=document.getElementById('register_password').value;
		pwd_c=document.getElementById('register_confirm').value;
		v=document.getElementById('register_verification').value;
		agr=document.getElementById('register_agreement').checked;
		if(user.length<2 || user.length>18)err+='用户名必须2到18个字符间\n';
		if(email=='')err+='邮箱不能为空\n';
		else if(!isEmail(email))err+='这不是一个正确的邮箱\n';
		if(pwd.length<6 || pwd.length>32)err+='密码必须为6到32个字符\n';
		else if(pwd!=pwd_c)err+='两次密码不一致\n';
		if(v.length!=4)err+='验证码长度为4个字符\n';
		if(agr==false)err+='必须同意网站协议才能注册\n';
		if(err.length==0){
			return true;
		}else{
			alert(err);
			return false;
		}
	}
	function isEmail(strEmail) {
		if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
			return true;
		else return false;
	}
	function loadXMLDoc(url)
	{
		xmlhttp=null;
		if (window.XMLHttpRequest)
		{// code for IE7, Firefox, Opera, etc.
			xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject)
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		if (xmlhttp!=null)
		{
			xmlhttp.onreadystatechange=state_Change;
			xmlhttp.open("GET",url,true);
			xmlhttp.send(null);
		}
		else
		{
			//alert("Your browser does not support XMLHTTP.");
		}
	}
	
	function state_Change()
	{
		if (xmlhttp.readyState==4)
		{// 4 = "loaded"
			if (xmlhttp.status==200)
			{// 200 = "OK"
				if(xmlhttp.responseText=='FALSE')document.getElementById('email_notice').innerHTML='邮箱被占用';	
			}
		}
	}
-->
</script>

</form>
<?php }else{?>
<div class="succefull">
<h2>用户成功注册</h2>
<ul>
<li><a href="<?=site_url('/user')?>">前往用户中心</a></li>
<li><a href="<?=site_url('/user/active')?>">激活账户</a></li>
<li><a href="<?=site_url()?>">返回网站首页</a></li>
<li><a href="<?=site_url('/fqa.html')?>">查看网站帮助</a></li>
</ul>
</div>

<?php }}else{?>

<div class="close">用户注册已关闭</div>

<?php }?>
</div>