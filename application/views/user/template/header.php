<?=doctype('html5');?>

<html>

<head>
<?=meta(array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'));?>

<title><?=$title,' - ',sitename();?></title>
<?=meta($meta);?>
<?=link_tag(array('href' => 'css/user/common.css','rel' => 'stylesheet','type' => 'text/css'));?>

<?php foreach($link_tag as $v)echo link_tag($v);unset($v);?>

</head>

<body>

<div id="wrap">

<div id="header">
	<h1 class="title"><a href="<?=site_url("user")?>">LMoney-user</a></h1>
	
	<table class="user"><tbody><tr>
		<td><img src="<?=get_user_avata()?>" class="avata" alt="avata" ></td>
		<td>
			<ul class="info">
				<li><?=get_user_name('<strong class="username">','</strong>')?></li>
				<li><?=get_logout_link(true)?></li>
			</ul>
		</td>
	</tr></tbody></table>
</div>

<div id="content">
<!-- 头部文件结束 -->

