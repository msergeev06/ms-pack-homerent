<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счета - Добавление');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bShowForm = true;
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Accounts::addFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Счёт успешно добавлен</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'accounts/',3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при добавлении счёта:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if ($bShowForm){
?>
<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
	<div class="form-group">
		<label for="name" class="control-label">Название<span style="color: red;">*</span></label>
		<?=InputText('name','','class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="realty" class="control-label">Объект недвижимости<span style="color: red;">*</span></label>
		<?=Lib\Realty::showSelectRealty()?>
	</div>
	<div class="form-group">
		<label for="personal_number" class="control-label">Лицевой счет<span style="color: red;">*</span></label>
		<?=InputText('personal_number','','class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="start_value" class="control-label">Начальная сумма<span style="color: red;">*</span></label>
		<?=InputNumber('start_value',0,false,false,0.01,'class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="start_date" class="control-label">Срок действия (начало)<span style="color: red;">*</span></label>
		<?=InputDate('start_date',date('Y-m-d'),false,false,'class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="end_date" class="control-label">Срок действия (окончание)</label>
		<?=InputDate('end_date','',false,false,'class="form-control"')?>
	</div>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success">Добавить</button>
		</div>
	</div>
</form>
<?
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
