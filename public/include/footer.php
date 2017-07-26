<?php

if ($curDir == "icar") $curDir = "main";
?><script type="text/javascript">
	$(document).on("ready",function(){
		$(".top_menu_link").each(function(){
			$(this).removeClass("selected");
		});
		$("#<?=$curDir?>").addClass("selected");
	});
</script><?
__include_once(\MSergeev\Core\Lib\Loader::getTemplate("homerent")."footer.php");