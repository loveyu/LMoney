<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id="active">
<?php if(!get_user_active()):?>
<?php if(isset($mail_sent)):

if($mail_sent):?>
<p class="succfuly">激活邮件已经发送，请注意查收</p>
<?php else:?>
<p class="error">邮件发送错误，请查看错误信息，或联系管理员</p>
<?php
if(!empty($this->safe->error)){
	echo "\n",'<div class="error_info">',"\n";
	foreach($this->safe->error as $e)
		echo "<p>$e</p>";
	echo '</div>',"\n";
}

endif;
elseif(isset($verification)):
if($verification):?>

<p>邮箱验证成功，账户已激活，你可以使用全部功能</p>

<?php else:?>

<p>邮箱验证失败</p>
<?php foreach($this->safe->error as $v)echo "<p>$v</p>\n";?>

<?php endif;else:?>
<p>您的账户当前未激活，请使用邮箱激活</p>
<table align="center" cellspacing="3">
<tbody>
<tr><th>邮箱:</th><td><?=get_user_email()?>&nbsp;<span><a href="<?=site_url("user/email")?>">修改邮箱</a></span></td></tr>
<tr><th></th><td><a href="<?=site_url("user/active/send_mail")?>" class="act">激活</a></td></tr>
</tbody>
</table>
<?php endif;else:?>

<p>您的邮箱:<?=get_user_email()?> 已经激活，不需要重复激活</p>

<?php endif;?>
</div>

</div><!-- page -->

</div><!-- safe -->
<div class="clear"></div>
