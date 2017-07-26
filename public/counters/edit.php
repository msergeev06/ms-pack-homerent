<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Редактирование');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bShowForm = true;
if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
{
	$counterID = intval($_REQUEST['id']);
}
else
{
	Lib\Errors::addError('EMPTY_COUNTER_ID','Не указан ID изменяемого счётчика');
	$counterID = null;
	$bShowForm = false;
}
if (!$arCounter = Lib\Counters::getList($counterID,true,1))
{
	Lib\Errors::addError('NO_DATA','Данные о счётчике не были загружены');
	$bShowForm = false;
}
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Counters::updateFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Информация о счётчике успешно изменена</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'counters/',3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при изменении информации о счётчике:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
elseif (Lib\Errors::issetErrors())
{
	?><div class="text-danger"><?=Lib\Errors::showErrorList()?></div><?
	$bShowForm = false;
}
if ($bShowForm){
	?>
	<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
		<div class="form-group">
			<label for="active" class="control-label">Активен<span style="color: red;">*</span></label>
			<?=SelectBoxBool('active',$arCounter['ACTIVE'],'','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="sort" class="control-label">Сортировка<span style="color: red;">*</span></label>
			<?=InputNumber('sort',$arCounter['SORT'],0,false,1,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="name" class="control-label">Название<span style="color: red;">*</span></label>
			<?=InputText('name',$arCounter['NAME'],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Описание</label>
			<?=InputText('description',$arCounter['DESCRIPTION'],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="account" class="control-label">Лицевой счёт<span style="color: red;">*</span></label>
			<?=Lib\Accounts::showSelectAccounts($arCounter['ACCOUNT_ID'])?>
		</div>
		<div class="form-group">
			<label for="decimal" class="control-label">Точность (кол-во знаков после ",")<span style="color: red;">*</span></label>
			<?=InputNumber('decimal',$arCounter['FORMAT_DECIMAL'],0,false,1,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_date" class="control-label">Ввод в эксплуатацию<span style="color: red;">*</span></label>
			<?=InputDate('start_date',$arCounter['START_DATE'],false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="end_date" class="control-label">Вывод из эксплуатации</label>
			<?=InputDate('end_date',!is_null($arCounter['END_DATE'])?$arCounter['END_DATE']:'',false,false,'class="form-control"')?>
		</div>
		<input type="hidden" name="action" value="1">
		<input type="hidden" name="id" value="<?=$counterID?>">
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
