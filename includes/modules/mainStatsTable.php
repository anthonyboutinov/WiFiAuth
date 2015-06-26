<h1>
	<i class="fa fa-table"></i>
	Краткий отчет
</h1>
<div class="page-wrapper">

	<table class="table table-condensed">
		<?php foreach ($database->getShortReport() as $key => $value) { ?>
		<tr><td>Сессий за <?=$key;?></td><td class="text-right"><?=$value;?></td></tr>
		<?php } ?>
	</table>

</div>