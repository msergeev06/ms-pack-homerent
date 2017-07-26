<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счета - Редактирование');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$dateHelper = new CoreLib\DateHelper();
if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
{
	$accountID = intval($_REQUEST['id']);
	$bShowForm = true;
}
else
{
	Lib\Errors::addError('EMPTY_ID','Не указан ID редактируемой записи. Продолжение невозможно');
	?><div class="text-danger"><br><?=Lib\Errors::showErrorList()?></div><?
	$accountID = null;
	$bShowForm = false;
}
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Accounts::updateFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Счёт успешно сохранен</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'accounts/',3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при сохранении счёта:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if ($bShowForm){
	$arAccount = Lib\Accounts::getList($accountID);
	if (is_null($arAccount['END_ACTIVE']))
	{
		$inputEndDate = '';
	}
	else
	{
		$inputEndDate = $dateHelper->convertDateToDB($arAccount['END_ACTIVE']);
	}
	?>
	<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
		<div class="form-group">
			<label for="active" class="control-label">Активен<span style="color: red;">*</span></label>
			<?=SelectBoxBool('active',$arAccount['ACTIVE'],'','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="sort" class="control-label">Сортировка<span style="color: red;">*</span></label>
			<?=InputNumber('sort',$arAccount['SORT'],0,false,1,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="name" class="control-label">Название<span style="color: red;">*</span></label>
			<?=InputText('name',$arAccount['NAME'],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="realty" class="control-label">Объект недвижимости<span style="color: red;">*</span></label>
			<?=Lib\Realty::showSelectRealty($arAccount['REALTY_ID'])?>
		</div>
		<div class="form-group">
			<label for="personal_number" class="control-label">Лицевой счет<span style="color: red;">*</span></label>
			<?=InputText('personal_number',$arAccount['PERSONAL_NUMBER'],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_value" class="control-label">Начальная сумма<span style="color: red;">*</span></label>
			<?=InputNumber('start_value',$arAccount['START_VALUE'],false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_date" class="control-label">Срок действия (начало)<span style="color: red;">*</span></label>
			<?=InputDate('start_date',$dateHelper->convertDateToDB($arAccount['START_ACTIVE']),false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="end_date" class="control-label">Срок действия (окончание)</label>
			<?=InputDate('end_date',$inputEndDate,false,false,'class="form-control"')?>
		</div>
		<input type="hidden" name="id" value="<?=intval($_REQUEST['id'])?>">
		<input type="hidden" name="action" value="1">
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
