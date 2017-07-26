<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Добавление нового тарифа');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bShowForm = true;
$arCounter = array();
if (!isset($_REQUEST['id']) || intval($_REQUEST['id'])<=0)
{
	Lib\Errors::addError('EMPTY_ID','Не указан ID счётчика');
	$counterID = null;
	$bShowForm = false;
}
else
{
	$counterID = intval($_REQUEST['id']);
	$arCounter = Lib\Counters::getList($counterID);
	//msDebug($arCounter);
}
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\CounterTariffs::addFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Тариф успешно добавлен</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'counters/tariffs.php?id='.$counterID,3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при добавлении тарифа:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if ($bShowForm){
	?>
	<p>Добавление нового тарифа для счётчика: [<?=$arCounter['ACCOUNT_REALTY_NAME']?>] <?=$arCounter['NAME']?> (<?=$arCounter['ACCOUNT_NAME']?>)</p>
	<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
		<div class="form-group">
			<label for="name" class="control-label">Название тарифа<span style="color: red;">*</span></label>
			<?=InputText('name','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="title" class="control-label">Описание тарифа</label>
			<?=InputText('title','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_date" class="control-label">Дата начала тарифа<span style="color: red;">*</span></label>
			<?=InputDate('start_date',date('Y-m-d'),false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="end_date" class="control-label">Дата окончания тарифа</label>
			<?=InputDate('end_date','',false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="tariff-type" class="control-label">Тип тарифа<span style="color: red;">*</span></label>
			<?=Lib\CounterTariffs::showSelectTariffTypes()?>
		</div>
		<div class="form-group field-sum">
			<label for="sum" class="control-label">Стоимость<span style="color: red;">*</span></label>
			<?=InputNumber('sum',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-benefit">
			<label for="sum-benefit" class="control-label">Льгота</label>
			<?=InputNumber('sum-benefit',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-before-threshold1">
			<label for="sum-before-threshold1" class="control-label">Стоимость до порога 1<span style="color: red;">*</span></label>
			<?=InputNumber('sum-before-threshold1',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-before-threshold1-benefit">
			<label for="sum-before-threshold1-benefit" class="control-label">Льгота для стоимости до порога 1</label>
			<?=InputNumber('sum-before-threshold1-benefit',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-threshold1">
			<label for="threshold1" class="control-label">Порог 1<span style="color: red;">*</span></label>
			<?=InputNumber('threshold1',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold1">
			<label for="sum-after-threshold1" class="control-label">Стоимость свыше порога 1<span style="color: red;">*</span></label>
			<?=InputNumber('sum-after-threshold1',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold1-benefit">
			<label for="sum-after-threshold1-benefit" class="control-label">Льгота для стоимости свыше порога 1</label>
			<?=InputNumber('sum-after-threshold1-benefit',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-threshold2">
			<label for="threshold2" class="control-label">Порог 2<span style="color: red;">*</span></label>
			<?=InputNumber('threshold2',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold2">
			<label for="sum-after-threshold2" class="control-label">Стоимость свыше порога 2<span style="color: red;">*</span></label>
			<?=InputNumber('sum-after-threshold2',0,false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold2-benefit">
			<label for="sum-after-threshold2-benefit" class="control-label">Льгота для стоимости свыше порога 2</label>
			<?=InputNumber('sum-after-threshold2-benefit',0,false,false,0.01,'class="form-control"')?>
		</div>
		<input type="hidden" name="action" value="1">
		<input type="hidden" name="id" value="<?=$counterID?>">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="submit btn btn-success">Добавить тариф</button>
			</div>
		</div>
	</form>
	<style>
		.field-sum-before-threshold1,
		.field-sum-before-threshold1-benefit,
		.field-threshold1,
		.field-sum-after-threshold1,
		.field-sum-after-threshold1-benefit,
		.field-threshold2,
		.field-sum-after-threshold2,
		.field-sum-after-threshold2-benefit {
			display: none;
		}
	</style>
	<script type="text/javascript">
		$(document).on('ready',function(){
			$('#tariff-type').on('change',function(){
				if ($(this).val()<=1)
				{
					$('.field-sum').show();
					$('.field-sum-benefit').show();
					$('.field-sum-before-threshold1').hide();
					$('.field-sum-before-threshold1-benefit').hide();
					$('.field-threshold1').hide();
					$('.field-sum-after-threshold1').hide();
					$('.field-sum-after-threshold1-benefit').hide();
					$('.field-threshold2').hide();
					$('.field-sum-after-threshold2').hide();
					$('.field-sum-after-threshold2-benefit').hide();
				}
				else if ($(this).val()==2)
				{
					$('.field-sum').hide();
					$('.field-sum-benefit').hide();
					$('.field-sum-before-threshold1').show();
					$('.field-sum-before-threshold1-benefit').show();
					$('.field-threshold1').show();
					$('.field-sum-after-threshold1').show();
					$('.field-sum-after-threshold1-benefit').show();
					$('.field-threshold2').hide();
					$('.field-sum-after-threshold2').hide();
					$('.field-sum-after-threshold2-benefit').hide();
				}
				else if ($(this).val()==3)
				{
					$('.field-sum').hide();
					$('.field-sum-benefit').hide();
					$('.field-sum-before-threshold1').show();
					$('.field-sum-before-threshold1-benefit').show();
					$('.field-threshold1').show();
					$('.field-sum-after-threshold1').show();
					$('.field-sum-after-threshold1-benefit').show();
					$('.field-threshold2').show();
					$('.field-sum-after-threshold2').show();
					$('.field-sum-after-threshold2-benefit').show();
				}
			});
		});
	</script>
<?
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
