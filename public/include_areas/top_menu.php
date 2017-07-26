<?
use MSergeev\Core\Lib;
$path = Lib\Tools::getSitePath(Lib\Loader::getPublic("homerent"));
$imgPath = Lib\Tools::getSitePath(Lib\Loader::getTemplate("homerent")."images/");
$imgWidth = $imgHeight = 30;
?>
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
			<li>
				<a href="<?=$path?>">Журнал</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/counters/'))?' active':''?>">
				<a href="<?=$path?>counters/">Счётчики</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/services/'))?' active':''?>">
				<a href="<?=$path?>services/">Услуги</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/graphics/'))?' active':''?>">
				<a href="<?=$path?>graphics/">Графики</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/operations/'))?' active':''?>">
				<a href="<?=$path?>operations/">Операции</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/notice/'))?' active':''?>">
				<a href="<?=$path?>notice/">Напоминания</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/accounts/'))?' active':''?>">
				<a href="<?=$path?>accounts/">Счета</a>
			</li>
			<li class="<?=(Lib\Tools::isDir('/realty/'))?' active':''?>">
				<a href="<?=$path?>realty/">Объекты недвижимости</a>
			</li>
		</ul>
	</div><!-- /.container-fluid -->
</nav>

<style>
	ul.nav li {
		text-align: center;
	}
</style>