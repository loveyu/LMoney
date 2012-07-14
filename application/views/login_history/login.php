
<div id="login">

<form action="<?php echo site_url("login_history/login/post");?>" method="post">
<ul>
<?php
if(!$pwd)echo "<li class=\"error\">密码错误</li>";
if($no_pwd)echo "<li class=\"error\">未设置查询密码,<a href=\"".site_url("user/HPIN")."\">-&gt;设置&lt;-</a></li>";
if($logout)echo "<li class=\"logout\">已退出</li>";
?>
<li>输入查询密码：</li>
<li><input name="HPIN" id="HPIN" type="password" value=""></li>
<li><button type="submit">确认</button></li>
</ul>
</form>
</div>
