<div id="safe">
<?php $this->load->view('user/template/safe_widget');?>
<div class="safe-page">

<div id"active">
<?php if($this->login->user_info->active=='FALSE'):?>
<?php if(isset($mail_sent)):


if($mail_sent):?>
<p>Send</p>
<?php else:?>
<p>Send Error</p>
<?php endif;
elseif(isset($verification)):
if($verification):?>

<p>verification OK</p>

<?php else:?>

<p>verification error</p>

<?php endif;else:?>
<p>您的账户当前为激活，请使用邮箱激活</p>
<table>
<tbody>
<tr><th>邮箱:</th><td><?=get_user_email()?><span>修改邮箱</span></td></tr>
<tr><th></th><td><a href="<?=site_url("user/active/send_mail")?>">激活</a></td></tr>
</tbody>
</table>
<?php endif;else:?>


<?php endif;?>
</div>

</div><!-- page -->

</div><!-- safe -->
<div class="clear"></div>
