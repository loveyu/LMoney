
<!-- 菜单文件开始 -->
<div id="menu">
<ul>
<li<?=get_menu_active('profile')?>><a href="<?=site_url("user/profile")?>">个人信息</a></li>
<li<?=get_menu_active('safe')?>><a href="<?=site_url("user/safe")?>">安全设置</a></li>
<li<?=get_menu_active('password')?>><a href="<?=site_url("user/password")?>">密码修改</a></li>
<li<?=get_menu_active('login')?>><a href="<?=site_url("login_history")?>">登录查询</a></li>
</ul>
</div>
<!-- 菜单文件结束 -->

