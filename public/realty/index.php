<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Объекты недвижимости');
use MSergeev\Packages\Homerent\Lib;
?>
<p>Список объектов, по которым ведется учёт</p><br>
<p><a href="add.php"><button type="submit" class="submit btn btn-primary">Добавить объект недвижимости</button></a></p><br>
<?Lib\Realty::showListTable()?><br>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
