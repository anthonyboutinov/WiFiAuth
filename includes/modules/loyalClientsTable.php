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
	if ($paginationOn) {
		if (!isset($_GET['offset'])) {
			$offset = 0;
		} else {
			$offset = $_GET['offset'];
		}
	} else {
		$offset = 0;
	}
	
	$limit = $drawFullContent ? $database->tablePageLimit : $database->dashboardTablePreviewLimit;
	
	$topUsers = $database->getTopUsers($offset, $limit);

?>
<h1 id="loyal-clients-h1"><i class="fa fa-heart hidden-md"></i> Постоянные пользователи</h1>
<div class="page-wrapper">

	<?php if ($drawBoth) { 	?><table class="table table-head-row text-center"><?php } 
		  else { 			?><table class="table text-right"><?php } ?>
		<?php if ($drawFullContent) { ?><tr class="head-row<?php if ($drawOnlyOne) { echo " text-center"; } ?>">
			<td id="loyal-clients-table-head-part-col-1">№</td>
			<td id="loyal-clients-table-head-part-col-2">Клиент</td>
			<td id="loyal-clients-table-head-part-col-3">Сессии</td>
		</tr>
	<?php }
		if ($drawBoth) { 	?>
	</table>
	<div class="scrollable gradient" id="loyal-clients-scrollable">
		<table class="table text-right">
	<?php } 
		
		if ($topUsers->num_rows > 0) {
			$i = 0;
			while($row = $topUsers->fetch_assoc()) {
				$i++;
	?>
			<tr>
				<?php if ($drawFullContent) { ?><td id="loyal-clients-table-scrollable-part-col-1"><?=$i;?></td><?php } ?>
				<td id="loyal-clients-table-scrollable-part-col-2" class="text-left"><a href="<?=$row['LINK'];?>" target="blank"><?=$row['NAME'];?></a></td>
				<td id="loyal-clients-table-scrollable-part-col-3"><?=$row['LOGIN_COUNT'];?></td>
			</tr>
	<?php 
			}
		} else { ?>
			<tr><td colspan="<?php if ($drawFullContent) echo '3'; else echo '2'; ?>" class="text-center">Пусто</td></tr>
	<?	} ?>
		</table>
	<?php if ($drawBoth) { echo "</div>"; } ?>
	
	<?php if (false && $drawOnlyOne && $paginationOn) { ?>
<!--
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
-->
	<?php } ?>
</div>