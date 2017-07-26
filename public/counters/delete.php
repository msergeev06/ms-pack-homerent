<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Удаление');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bError = false;
if (isset($_REQUEST['id']))
{
	$deleteID = intval($_REQUEST['id']);
}
else
{
	Lib\Errors::addError('EMPTY_ID','Отсутсвует ID удаляемого объекта. Продолжение невозможно');
	?><div class="text-danger"><?=Lib\Errors::showErrorList()?></div><?
	$deleteID = null;
	$bError = true;
}

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Counters::deleteFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Счётчик успешно удалён</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'counters/',3);
		//msDebug(CoreLib\Loader::getSitePublic('homerent').'realty/');
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при удалении счётчика:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if (!$bError){
	$arDelete = Lib\Counters::getList($deleteID);
	$bCanDelete = Lib\Counters::checkCanDelete($deleteID);
	if ($arDelete){
		?>
		<form class="form-horizontal" role="form" name="realty_edit" method="post" action="">
			<?if(!$bCanDelete):?>
				<div class="form-group text-warning"><?=Lib\Errors::showErrorList()?></div>
			<?endif;?>
			<div class="form-group">
				<label for="confirm" class="control-label">Подтверждаю, что хочу удалить счётчик "<?=$arDelete['NAME']?>"
					с ID=<?=$arDelete['ID']?>!</label>
				<?=SelectBoxBool('confirm',0,'Подтверждаю','Не подтверждаю','class="form-control"')?>
			</div>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="id" value="<?=$deleteID?>">
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="submit btn btn-danger">Удалить</button>
				</div>
			</div>
		</form>
	<?
	}
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
