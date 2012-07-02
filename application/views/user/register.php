<div id="register">

<?php
if(isset($_GET['close']))$close=TRUE;
if(isset($_GET['status']))$status=TRUE;
?>
<?php if(!$close){
if(!$status){
?>
<h2>用户注册</h2>
<form action="register.html" method="post" autocomplete="off">
<?php
echo validation_errors('<p class="register_error">', '</p>');
echo $this->register->register_errors('<p class="register_error">', '</p>');
?>
<table>
<tbody>
<tr><th><label for="register_user">用&nbsp;户&nbsp;名</label></th><td><input name="register[user]" id="register_user" value="<?=set_value('register[user]')?>" type="text"/></td><td></td></tr>
<tr><th><label for="register_email">邮&nbsp;&nbsp;&nbsp;&nbsp;箱</label></th><td><input name="register[email]" id="register_email" value="<?=set_value('register[email]')?>" type="text"/></td><td></td></tr>
<tr><th><label for="register_password">密&nbsp;&nbsp;&nbsp;&nbsp;码</label></th><td><input name="register[password]" id="register_password" type="password" /></td><td></td></tr>
<tr><th><label for="register_confirm">确认密码</label></th><td><input id="register_confirm" name="register[confirm]" type="password"/></td><td></td></tr>
<tr><th><label for="register_verification">验&nbsp;证&nbsp;码</label></th><td><input name="register[verification]" id="register_verification" type="text" /></td><td><img src="<?php echo base_url('func/verification_code');?>" align="absmiddle" onclick="this.src=this.src" title="点击刷新"></td></tr>
</tbody>
</table>

<p class="agreement"><input type="checkbox" id="register_agreement" name="register[agreement]" <?php echo set_checkbox('register[agreement]', '1');?> value="1" /><label for="register_agreement">同意网站注册协议</label></p>
<button type="submit" class="submit">注册</button>

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