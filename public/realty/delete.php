<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Объекты недвижимости - Удаление');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bError = false;
if (isset($_REQUEST['id']))
{
	$realtyID = intval($_REQUEST['id']);
}
else
{
	Lib\Errors::addError('EMPTY_ID','Отсутсвует ID удаляемого объекта. Продолжение невозможно');
	?><div class="text-danger"><?=Lib\Errors::showErrorList()?></div><?
	$realtyID = null;
	$bError = true;
}

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Realty::deleteFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Объект недвижимости успешно удален</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'realty/',3);
		msDebug(CoreLib\Loader::getSitePublic('homerent').'realty/');
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при удалении объекта недвижимости:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if (!$bError){
	$arRealty = Lib\Realty::getList($realtyID);
	$bCanDelete = Lib\Realty::checkCanDelete($realtyID);
	if ($arRealty){
	?>
	<form class="form-horizontal" role="form" name="realty_edit" method="post" action="">
		<?if(!$bCanDelete):?>
		<div class="form-group text-warning"><?=Lib\Errors::showErrorList()?></div>
		<?endif;?>
		<div class="form-group">
			<label for="confirm" class="control-label">Подтверждаю, что хочу удалить объект недвижимости "<?=$arRealty['NAME']?>"
			с ID=<?=$arRealty['ID']?>!</label>
			<?=SelectBoxBool('confirm',0,'Подтверждаю','Не подтверждаю','class="form-control"')?>
		</div>
		<input type="hidden" name="action" value="1">
		<input type="hidden" name="id" value=<?=$realtyID?>">
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
