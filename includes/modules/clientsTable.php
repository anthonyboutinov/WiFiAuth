<?php
	
	if (!isset($drawBoth)) {
		$drawBoth = false;
	}
	if (!isset($drawOnlyOne)) {
		$drawOnlyOne = true;
	}
	if (!isset($paginationOn)) {
		$paginationOn = true;
	}
	
	
	if ($paginationOn || $drawOnlyOne) {
		if (!isset($_GET['offset'])) {
			$offset = 0;
		} else {
			$offset = $_GET['offset'];
		}
	} else {
		$offset = 0;
	}
	
	$limit = $drawFullContent ? $database->tablePageLimit : $database->dashboardTablePreviewLimit;
	
	$users = $database->getLoginActs($offset, $limit);

?>
<h1 id="clients-h1"><i class="fa fa-users"></i> Недавние пользователи</h1>
<div class="page-wrapper">

	<?php if ($drawBoth) { 	?><table class="table table-head-row text-center"><?php } 
		  else { 			?><table class="table text-right contains-fas"><?php } ?>
		<?php if ($drawFullContent) { ?><tr class="head-row">
			<td id="clients-table-head-part-col-1">№</td>
			<td id="clients-table-head-part-col-2">Дата</td>
			<td id="clients-table-head-part-col-3"><span class="sr-only">Логин</span></td>
			<td id="clients-table-head-part-col-4">Клиент</td>
		</tr>
	<?php }
		if ($drawBoth) { ?>
	</table>
	<div class="scrollable gradient" id="clients-scrollable">
		<table class="table text-right contains-fas">
	<?php
		}
		
		if ($users->num_rows > 0) {
			$i = 0;
			while($row = $users->fetch_assoc()) {
				$i++;
	?>
			<tr>
				<?php if ($drawFullContent) { ?><td id="clients-table-scrollable-part-col-1" class="text-right"><?=$i;?></td><?php } ?>
				<td id="clients-table-scrollable-part-col-2"><span class="hidden-xs"><?=substr($row['LOGIN_DAY'], 0, 5).', ';?></span><?=$row['LOGIN_TIME'];?></td>
				<td id="clients-table-scrollable-part-col-3" class="text-center">
					<span class="fa-stack"><i class="fa fa-<?=$row['LOGIN_OPTION_SHORT_NAME'];?>"></i></span>
					<span class="sr-only"><?=$row['LOGIN_OPTION_NAME'];?></span>
				</td>
				<td id="clients-table-scrollable-part-col-4" class="text-left"><a href="<?=$row['LINK'];?>" target="blank"><?=$row['NAME'];?></a></td>
			</tr>
	<?php 
			}
		} else { ?>
			<tr><td colspan="4" class="text-center">Пусто</td></tr>
	<? }
	?>
		</table>
	<?php if ($drawBoth) { echo "</div>"; } ?>
	
	<?php if ($drawOnlyOne && $paginationOn) { ?>
	<nav class="text-center">
		<ul class="pagination pagination-lg">
			<li class="disabled"><a href="#" aria-label="Предыдущая"><span aria-hidden="true">&laquo;</span></a></li>
			<li class="active"><a href="#">1<span class="sr-only"> (текущая)</span></a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li>
				<a href="#" aria-label="Следующая">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
	<?php } ?>
</div>