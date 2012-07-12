
<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id="edit_password">
<h2>修改用户密码</h2>
<?php if($status){?>
<p>成功修改用户密码，请谨记</p>
<?php }else{?>
<form action="<?php echo site_url('user/password/post'); ?>" method="post" onSubmit="return V_CheckPassword();">
<table align="center" cellspacing="3">
<tbody>
<?php echo validation_errors('<tr><td colspan="3" class="error">','</td></tr>'),$this->safe->safe_errors('<tr><td colspan="3" class="error">','</td></tr>');?>
<tr><th>原密码:</th><td><input name="password[old]" type="password" id="old_password" value="" onBlur="check_old();" /></td><td id="old_notice" class="notic_error"></td></tr>
<tr><th>新密码:</th><td><input name="password[new]" type="password" id="new_password" value="" onBlur="check_new();" /></td><td id="new_notice" class="notic_error"></td></tr>
<tr><th>确&nbsp;&nbsp;认:</th><td><input name="password[confirm]" type="password" id="confirm_password" value="" onBlur="check_confirm();" /></td><td id="confirm_notice" class="notic_error"></td></tr>
<tr><th>验证码:</th><td><input name="password[v]" type="text" id="v_password" value="" /></td><td><img src="<?php echo base_url('func/verification_code');?>" align="absmiddle" onclick="this.src='<?php echo base_url('func/verification_code');?>?'+Math.random();" title="点击刷新"></td></tr>
<tr><td colspan="3" style="text-align:center;"><button type="submit" class="submit">确认修改</button></td></tr>
</tbody>
</table>
</form>
<script language="javascript"><!--
	var n='6到32个字符';
	function check_old(){
		document.getElementById('old_notice').innerHTML='';
		pwd=document.getElementById('old_password').value;
		if(pwd.length>32 || pwd.length<6){
			document.getElementById('old_notice').innerHTML=n;
			return false;
		}
		return true;
	}
	function check_new(){
		document.getElementById('new_notice').innerHTML='';
		document.getElementById('confirm_notice').innerHTML='';
		pwd=document.getElementById('new_password').value;
		if(pwd.length>32 || pwd.length<6){
			document.getElementById('new_notice').innerHTML=n;
			return false;
		}
		if(pwd==document.getElementById('old_password').value){
			document.getElementById('new_notice').innerHTML="不能使用相同的密码";
			return false;
		}
		return true;
	}	
	function check_confirm(){
		document.getElementById('confirm_notice').innerHTML='';
		pwd=document.getElementById('confirm_password').value;
		if(pwd.length>32 || pwd.length<6){
			document.getElementById('confirm_notice').innerHTML=n;
			return false;
		}
		if(pwd!=document.getElementById('new_password').value){
			document.getElementById('confirm_notice').innerHTML="两次密码不一致";
			return false;			
		}
		return true;
	}
	function V_CheckPassword(){
		if(document.getElementById('v_password').value.length!=4){
			alert("验证码长度不符");
			return false;s
		}
		if(check_old() && check_new() && check_confirm())return true;
		else return false;
	}
--></script>
<?php }?>
</div><!-- password -->

</div><!-- safe-page -->

</div><!-- safe -->

<div class="clear"></div>

