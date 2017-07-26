<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики - Редактирование тарифа');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

$bShowForm = true;
$arCounter = array();
$dateHelper = new CoreLib\DateHelper();
$arTariff = array();
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
if (!isset($_REQUEST['tariff']) || intval($_REQUEST['tariff'])<=0)
{
	Lib\Errors::addError('EMPTY_TARIFF_ID','Не указан ID тарифа');
	$tariffID = null;
	$bShowForm = false;
}
else
{
	$tariffID = intval($_REQUEST['tariff']);
	$arTariff = Lib\CounterTariffs::getList($tariffID);
	$arTariff['START_DB'] = '';
	if (!is_null($arTariff['START']) && $dateHelper->validateDate($arTariff['START']))
	{
		$arTariff['START_DB'] = $dateHelper->convertDateToDB($arTariff['START']);
	}
	$arTariff['END_DB'] = '';
	if (!is_null($arTariff['END']) && $dateHelper->validateDate($arTariff['END']))
	{
		$arTariff['END_DB'] = $dateHelper->convertDateToDB($arTariff['END']);
	}
	msDebug($arTariff);
}
if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\CounterTariffs::addFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Тариф успешно изменен</div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('homerent').'counters/tariffs.php?id='.$counterID,3);
		$bShowForm = false;
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при изменении тарифа:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
if ($bShowForm){
	?>
	<p>Изменение тарифа для счётчика: [<?=$arCounter['ACCOUNT_REALTY_NAME']?>] <?=$arCounter['NAME']?> (<?=$arCounter['ACCOUNT_NAME']?>)</p>
	<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
		<div class="form-group">
			<label for="active" class="control-label">Активен<span style="color: red;">*</span></label>
			<?=SelectBoxBool('active',$arTariff['ACTIVE'],'','','class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="sort" class="control-label">Сортировка<span style="color: red;">*</span></label>
			<?=InputNumber('sort',$arTariff['SORT'],0,false,1,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="name" class="control-label">Название тарифа<span style="color: red;">*</span></label>
			<?=InputText('name',$arTariff["NAME"],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="title" class="control-label">Описание тарифа</label>
			<?=InputText('title',$arTariff["TITLE"],'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="start_date" class="control-label">Дата начала тарифа<span style="color: red;">*</span></label>
			<?=InputDate('start_date',$arTariff['START_DB'],false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="end_date" class="control-label">Дата окончания тарифа</label>
			<?=InputDate('end_date',$arTariff['END_DB'],false,false,'class="form-control"')?>
		</div>
		<div class="form-group">
			<label for="tariff-type" class="control-label">Тип тарифа<span style="color: red;">*</span></label>
			<?=Lib\CounterTariffs::showSelectTariffTypes($arTariff['TYPE'])?>
		</div>
		<div class="form-group field-sum">
			<label for="sum" class="control-label">Стоимость<span style="color: red;">*</span></label>
			<?=InputNumber('sum',floatval($arTariff['SUM']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-benefit">
			<label for="sum-benefit" class="control-label">Льгота</label>
			<?=InputNumber('sum-benefit',floatval($arTariff['SUM_BENEFIT']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-before-threshold1">
			<label for="sum-before-threshold1" class="control-label">Стоимость до порога 1<span style="color: red;">*</span></label>
			<?=InputNumber('sum-before-threshold1',floatval($arTariff['SUM_BEFORE_THRESHOLD1']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-before-threshold1-benefit">
			<label for="sum-before-threshold1-benefit" class="control-label">Льгота для стоимости до порога 1</label>
			<?=InputNumber('sum-before-threshold1-benefit',floatval($arTariff['SUM_BEFORE_THRESHOLD1_BENEFIT']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-threshold1">
			<label for="threshold1" class="control-label">Порог 1<span style="color: red;">*</span></label>
			<?=InputNumber('threshold1',floatval($arTariff['THRESHOLD1']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold1">
			<label for="sum-after-threshold1" class="control-label">Стоимость свыше порога 1<span style="color: red;">*</span></label>
			<?=InputNumber('sum-after-threshold1',floatval($arTariff['SUM_AFTER_THRESHOLD1']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold1-benefit">
			<label for="sum-after-threshold1-benefit" class="control-label">Льгота для стоимости свыше порога 1</label>
			<?=InputNumber('sum-after-threshold1-benefit',floatval($arTariff['SUM_AFTER_THRESHOLD1_BENEFIT']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-threshold2">
			<label for="threshold2" class="control-label">Порог 2<span style="color: red;">*</span></label>
			<?=InputNumber('threshold2',floatval($arTariff['THRESHOLD2']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold2">
			<label for="sum-after-threshold2" class="control-label">Стоимость свыше порога 2<span style="color: red;">*</span></label>
			<?=InputNumber('sum-after-threshold2',floatval($arTariff['SUM_AFTER_THRESHOLD2']),false,false,0.01,'class="form-control"')?>
		</div>
		<div class="form-group field-sum-after-threshold2-benefit">
			<label for="sum-after-threshold2-benefit" class="control-label">Льгота для стоимости свыше порога 2</label>
			<?=InputNumber('sum-after-threshold2-benefit',floatval($arTariff['SUM_AFTER_THRESHOLD2_BENEFIT']),false,false,0.01,'class="form-control"')?>
		</div>
		<input type="hidden" name="action" value="1">
		<input type="hidden" name="id" value="<?=$counterID?>">
		<input type="hidden" name="tariff" value="<?=$tariffID?>">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="submit btn btn-success">Сохранить изменения</button>
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
			var tariff = $('#tariff-type').val();
			changeTariff(tariff);
			$('#tariff-type').on('change',function(){
				changeTariff($(this).val());
			});
		});
		function changeTariff (tID)
		{
			if (tID<=1)
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
			else if (tID==2)
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
			else if (tID==3)
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
		}
	</script>
<?
}
?>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
