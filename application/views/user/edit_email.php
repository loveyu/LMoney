
<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id="edit_email">
<?php if($status){echo "<p style=\"color:blue;font-size:19px;text-align:center;\">成功修改邮箱</p>\n";}else{?>

<form action="<?php echo site_url("user/email/post")?>" method="post">
<table border="0" align="center" cellspacing="5">
<tbody>
<?php echo validation_errors('<tr><td colspan="2" class="error">','</td></tr>'),$this->safe->safe_errors('<tr><td colspan="2" class="error">','</td></tr>');?>

<tr><td colspan="2" id="status"></td></tr>
<tr><th>当前邮箱:</th><td><?php echo get_user_email()?>(<?php echo (get_user_active())?"已激活":"未激活";?>)</td></tr>
<tr><th><label for="new_email">新邮箱:</label></th><td><input name="new_email" id="new_email" type="text" value="<?=set_value('new_email')?>" onBlur="check_email();"/></td></tr>
<tr><th><label for="v_code">验证码:</label></th><td><input name="v_code" id="v_code" type="text" value="<?=set_value('v_code')?>" /></td></tr>
<tr><td colspan="2"><button type="button" onclick="return send_mail(this)">发送验证码</button><button class="submit" type="submit">确认修改</button></td></tr>
</tbody>
</table>
</form>
<script language="javascript">
<!--
	function check_email(){
		mail=document.getElementById('new_email').value;
		document.getElementById('status').innerHTML="";
		document.getElementById('status').style.border='';
		document.getElementById('status').style.color='red';
		if(!isEmail(mail)){
			document.getElementById('status').style.border='1px solid #ccccee';
			document.getElementById('status').innerHTML="邮箱不正确"
			return "邮箱不正确";
		}else{
			url='<?php echo site_url('func/CheckAccount/email?v=');?>'+mail;
			loadXMLDoc(url,true);
		}
	}
	function send_mail(v){
		if(v.innerHTML=="发送验证码"){
			loadXMLDoc('<?php echo site_url('user/email/send_mail')?>',false);
			v.innerHTML="验证码已发送";
			v.disabled=true;
			v.style.color='#cccccc';
		}
		return false;
	}
	function loadXMLDoc(url,check)
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
			if(check==true)xmlhttp.onreadystatechange=state_Change;
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
				document.getElementById('status').style.border='1px solid #ccccee';
				if(xmlhttp.responseText=='FALSE'){
					document.getElementById('status').innerHTML='邮箱被占用';	
				}else{
					document.getElementById('status').innerHTML='邮箱可以使用';	
					document.getElementById('status').style.color='blue';
				}
			}
		}
	}
	function isEmail(strEmail) {
		if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
			return true;
		else return false;
	}
-->
</script>

<?php }?>
</div>

</div><!-- page -->

</div><!-- safe -->
<div class="clear"></div>
