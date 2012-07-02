<?=doctype('html5');?>

<html>

<head>
<?=meta(array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'));?>

<title><?=$title,' - ',sitename();?></title>
<?=meta($meta);?>
<?=link_tag(array('href' => 'css/common.css','rel' => 'stylesheet','type' => 'text/css'));?>

<?php foreach($link_tag as $v)echo link_tag($v);unset($v);?>

</head>

<body>

<div id="wrap">

<div id="header">
	<h1 class="title"><a href="<?=site_url()?>">LMoney</a></h1>
</div>

<div id="content">
<!-- 头部文件结束 -->

