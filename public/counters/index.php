<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle('Счётчики');
use MSergeev\Packages\Homerent\Lib;
?>
<p>Здесь хранится информация о счётчиках - приборах учета, которые используются для подсчёта расходов различных ресурсов.</p><br>
<p><a href="add.php"><button type="submit" class="submit btn btn-primary">Добавить счётчик</button></a></p><br>
<?Lib\Counters::showListTable()?><br>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."../include/footer.php"); ?>
