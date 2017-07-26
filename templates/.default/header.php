<?
use MSergeev\Core\Lib;
//define("SHOW_SQL_WORK_TIME",true);

header('Content-type: text/html; charset=utf-8');
Lib\Buffer::start("page");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Коммунальные платежи - <?=Lib\Buffer::showTitle("Журнал");?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?=Lib\Buffer::showRefresh()?>
	<?=Lib\Buffer::showCSS()?>
	<?=Lib\Buffer::showJS()?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<div class="container">
	<? include_once (Lib\Loader::getPublic("homerent")."include_areas/top_menu.php"); ?>

	<h1><?=Lib\Buffer::showTitle("Главная");?></h1>
