<div id="profile">
<form action="<?php echo site_url("user/profile/edit_name");?>" method="post"  onSubmit="return check_form();">
<table border="1" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr><th colspan="3">个人信息查看与修改</th></tr>
<?php echo validation_errors('<tr><th></th><td colspan="2" class="error">','</td></tr>'),$this->safe->safe_errors('<tr><th></th><td colspan="2"  class="error">','</td></tr>');?>
<tr><th>用&nbsp;户&nbsp;名</th><td><input id="username" type="text" name="new_name" value="<?php echo set_value('new_name')?set_value('new_name'):get_user_name();?>" onBlur="check_form();"/><span id="err_notice"><?php if($status)echo "成功修改用户名";?></span></td><td><button class="submit" type="submit">保存</button></td></tr>
<tr><th>头&nbsp;&nbsp;&nbsp;&nbsp;像</th><td><img class="avata" src="<?php echo get_user_avata(); ?>" alt="avata"></td><td><button class="edit" type="button" onClick="edit_avata();">修改</button></td></tr>
<tr id="edit_avata" style="display:none;"><td colspan="3">网站使用Gravatar作为网站头像，<a href="http://en.gravatar.com" target="_blank" >前往</a></td></tr>
<tr><th>邮&nbsp;&nbsp;&nbsp;&nbsp;箱</th><td><span><?php echo get_user_email()?>(<?php echo (get_user_active())?"已激活":'<a href="'.site_url("user/active").'" target="_blank">未激活</a>';?>)</span></td><td><a href="<?php echo site_url("user/email")?>" target="_blank">修改</a></td></tr>
<tr><th>PIN</th><td><span><?php echo $this->safe->get_PIN('PIN')?"已设定":"未设置";?></span></td><td><a href="<?php echo site_url("user/PIN");?>" target="_blank"><?php echo $this->safe->get_PIN('PIN')?"修改":"创建";?></a></td></tr>
<tr><th>查询密码</th><td><span><?php echo $this->safe->get_PIN('HPIN')?"已设定":"未设置";?></span></td><td><a href="<?php echo site_url("user/HPIN");?>" target="_blank"><?php echo $this->safe->get_PIN('HPIN')?"修改":"创建";?></a></td></tr>
</tbody>
</table>
</form>
</div>
<script language="javascript"><!--
	var now_name="<?php echo get_user_name();?>";
	function edit_avata(){
		v=document.getElementById('edit_avata').style.display;
		if(v=='table-row')
			document.getElementById('edit_avata').style.display='none';
		if(v=='none')
			document.getElementById('edit_avata').style.display='table-row';
	}
	function check_form(){
		name=document.getElementById('username').value;
		document.getElementById('err_notice').innerHTML='';
		if(name.length>18 || name.length<2){
			document.getElementById('err_notice').innerHTML='长度为4到18';
			return false;
		}
		if(name==now_name){
			document.getElementById('err_notice').innerHTML='无需修改';
			return false;			
		}
		return true;
	}
--></script>