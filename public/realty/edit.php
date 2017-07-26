<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Объекты недвижимости - Редактирование');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bError = false;
if (isset($_REQUEST['id']))
{
	$realtyID = intval($_REQUEST['id']);
}
else
{
	Lib\Errors::addError('EMPTY_ID','Отсутсвует ID редактируемого объекта. Продолжение невозможно');
	?><div class="text-danger"><?=Lib\Errors::showErrorList()?></div><?
	$realtyID = null;
	$bError = true;
}

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Realty::updateFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Объект недвижимости успешно сохранен</div><?
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при сохранении объекта недвижимости:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if (!$bError){
	$arRealty = Lib\Realty::getList($realtyID);
?>
<form class="form-horizontal" role="form" name="realty_edit" method="post" action="">
	<div class="form-group">
		<label for="name" class="control-label">Название</label>
		<?=InputText('name',$arRealty['NAME'],'class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="active" class="control-label">Активен</label>
		<?=SelectBoxBool('active',$arRealty['ACTIVE'],'','','class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="sort" class="control-label">Сортировка</label>
		<?=InputNumber('sort',$arRealty['SORT'],0,false,1,'class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="address" class="control-label">Адрес</label>
		<?=InputText('address',$arRealty['ADDRESS'],'class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="description" class="control-label">Описание</label>
		<?=TextArea('description',$arRealty['DESCRIPTION'],'class="form-control"')?>
	</div>
	<input type="hidden" name="action" value="1">
	<input type="hidden" name="id" value="<?=$realtyID?>">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success">Сохранить</button>
		</div>
	</div>
</form>
<?
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
