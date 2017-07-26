<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Тарифы');
use MSergeev\Packages\Homerent\Lib;
use MSergeev\Core\Lib as CoreLib;
if (!isset($_REQUEST['id']) || intval($_REQUEST['id'])<=0)
{
	Lib\Errors::addError('EMPTY_ID','Не указан ID счётчика');
	$counterID = null;
}
else
{
	$counterID = intval($_REQUEST['id']);
	$arCounter = Lib\Counters::getList($counterID);
	?>
	<p>Управление тарифами счётчика: [<?=$arCounter['ACCOUNT_REALTY_NAME']?>] <?=$arCounter['NAME']?> (<?=$arCounter['ACCOUNT_NAME']?>)</p><br>
	<p><a href="tariff_add.php?id=<?=$counterID?>"><button type="submit" class="submit btn btn-primary">Добавить новый тариф</button></a></p><br>
	<?Lib\CounterTariffs::showListTable($counterID)?><br>
	<p><a href="<?=CoreLib\Loader::getSitePublic('homerent').'counters/'?>"><button type="submit" class="submit btn btn-default">К списку счётчиков</button></a></p><br>
<?
}
?>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
