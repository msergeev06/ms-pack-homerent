<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счета');
use MSergeev\Packages\Homerent\Lib;
?>
<p>Счета необходимы для того, чтобы вести учёт сумм фактической оплаты, включая переплату и задолженности</p><br>
<p><a href="add.php"><button type="submit" class="submit btn btn-primary">Добавить счёт</button></a></p><br>
<?Lib\Accounts::showListTable()?><br>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
