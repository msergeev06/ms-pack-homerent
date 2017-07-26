<?php

include_once ($_SERVER["DOCUMENT_ROOT"]."/msergeev_config.php");
MSergeev\Core\Lib\Loader::IncludePackage("homerent");
__include_once(MSergeev\Core\Lib\Loader::getTemplate("homerent")."header.php");

MSergeev\Core\Lib\Buffer::addCSS(MSergeev\Core\Lib\Loader::getTemplate("homerent")."css/style.css");
MSergeev\Core\Lib\Buffer::addJS(MSergeev\Core\Lib\Config::getConfig("CORE_ROOT")."js/jquery-1.11.3.min.js");
MSergeev\Core\Lib\Buffer::addJS(MSergeev\Core\Lib\Loader::getTemplate("homerent")."js/script.js");
\MSergeev\Core\Lib\Plugins::includeBootstrapCss();
\MSergeev\Core\Lib\Plugins::includeBootstrapJs();

$path=MSergeev\Core\Lib\Loader::getSitePublic('homerent');
