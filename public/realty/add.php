<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Объекты недвижимости - Добавление');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Lib;

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\Realty::addFromPost($_POST);
	if ($res)
	{
		?><div class="text-success">Объект недвижимости успешно добавлен</div><?
	}
	else
	{
		?><div class="text-danger">Возникли ошибки при добавлении объекта недвижимости:<br><?=Lib\Errors::showErrorList()?></div><?
	}
}
?>
<form class="form-horizontal" role="form" name="realty_add" method="post" action="">
	<div class="form-group">
		<label for="name" class="control-label">Название</label>
		<?=InputText('name','','class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="address" class="control-label">Адрес</label>
		<?=InputText('address','','class="form-control"')?>
	</div>
	<div class="form-group">
		<label for="description" class="control-label">Описание</label>
		<?=TextArea('description','','class="form-control"')?>
	</div>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success">Добавить</button>
		</div>
	</div>
</form>



<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
