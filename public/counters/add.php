<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Добавление');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bShowForm = true;
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Counters::addFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Счётчик успешно добавлен</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'counters/',3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при добавлении счётчика:<br><?=Lib\Errors::showErrorList()?></div><?
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
			<label for="description" class="control-label">Описание</label>
			<?=InputText('description','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="account" class="control-label">Лицевой счёт<span style="color: red;">*</span></label>
			<?=Lib\Accounts::showSelectAccounts()?>
		</div>
		<div class="form-group">
			<label for="decimal" class="control-label">Точность (кол-во знаков после ",")<span style="color: red;">*</span></label>
			<?=InputNumber('decimal',2,0,false,1,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_date" class="control-label">Ввод в эксплуатацию<span style="color: red;">*</span></label>
			<?=InputDate('start_date',date('Y-m-d'),false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="end_date" class="control-label">Вывод из эксплуатации</label>
			<?=InputDate('end_date','',false,false,'class="form-control"')?>
		</div>
		<input type="hidden" name="action" value="1">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="submit btn btn-success">Добавить счётчик</button>
			</div>
		</div>
	</form>
<?
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
