<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id="edit_password">
<h2>修改机密PIN密码</h2>


<form action="<?php echo site_url("user/PIN/".$type);?>" method="post" onSubmit="return V_CheckPassword();">
<table>
<tbody>
<?php echo validation_errors('<tr><td colspan="3" class="error">','</td></tr>'),$this->safe->safe_errors('<tr><td colspan="3" class="error">','</td></tr>');?>
<?php if($type=='creat'){?>
<tr><td colspan="3" class="table_notice"><?php echo $status?'成功创建PIN密码，请牢记':'还没有设置PIN密码，请创建新密码';?></td></tr>
<?php } if($type=='edit' || $status){
if($type=='edit' && $status){?>
<tr><td colspan="3" class="table_notice">成功修改PIN,请牢记</td></tr>
<?php }?>
<tr><th>原始密码:</th><td><input name="pwd[old]" type="password" id="old_password" value="" onBlur="check_old();"/></td><td id="old_notice" class="notic_error"></td></tr>
<?php }?>
<tr><th>新的密码:</th><td><input name="pwd[new]" type="password" value="" id="new_password" onBlur="check_new();"/></td><td id="new_notice" class="notic_error"></td></tr>
<tr><th>确认密码:</th><td><input name="pwd[confirm]" type="password" value="" id="confirm_password" onBlur="check_confirm();"/></td><td id="confirm_notice" class="notic_error"></td></tr>
<tr><td colspan="3" style="text-align:center;"><button id="submit_button" class="submit" type="submit"><?php echo ($type=='creat')?'创建密码':'修改密码';?></button></td></tr>
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
		old=document.getElementById('old_password').value;
		if(document.getElementById('submit_button').innerHTML=='修改密码' && pwd==old){
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
		if(  ( document.getElementById('submit_button').innerHTML=='创建密码'|| check_old()) && check_new() && check_confirm())return true;
		else return false;
	}
--></script>
</div>
</div><!-- safe-page -->
</div><!-- safe-->
<div class="clear"></div>