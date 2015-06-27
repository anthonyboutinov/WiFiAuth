<?php

	$desktop = true;	
	if (isset($_GET["mobile"])) {
		$desktop = false;
	}
	
	if (!isset($paginationOn)) {
		$paginationOn = true;
	}
	
	
	if ($paginationOn || !$desktop) {
		if (!isset($_GET['offset'])) {
			$offset = 0;
		} else {
			$offset = $_GET['offset'];
		}
	} else {
		$offset = 0;
	}
	
	$limit = $drawFullContent ? $database->tablePageLimit : $database->dashboardTablePreviewLimit;
	
	$birthdays = $database->getBirthdays($offset, $limit);
	

?>
<h1><i class="fa fa-birthday-cake"></i> Дни рождения</h1>
<div class="page-wrapper">
	
	<?php if ($desktop) { 	?><table class="table table-head-row text-center"><?php } 
		  else { 			?><table class="table text-center"><?php } ?>
		<?php if ($drawFullContent) { ?><tr class="head-row">
			<td>№</td>
			<td>Клиент</td>
			<td>День рождения</td>
		</tr>
	<?php }
		if ($desktop) { ?>
	</table>
	<div class="scrollable gradient">
		<table class="table text-center">
	<?php
		}
		
		if ($birthdays->num_rows > 0) {
			$i = 0;
			while($row = $birthdays->fetch_assoc()) {
				$i++;
	?>

			<tr>
				<?php if ($drawFullContent) { ?><td id="table-scrollable-part-col-1" class="text-right"><?=$i;?></td><?php } ?>
				<td id="table-scrollable-part-col-2" class="text-left"><a href="<?=$row['LINK'];?>" target="blank"><?=$row['NAME'];?></a></td>
				<td id="table-scrollable-part-col-3"><?=$row['BIRTHDAY'];?></td>
			</tr>
	<?php 
			}
		} else { ?>
			<tr><td colspan="<?php if ($drawFullContent) echo '3'; else echo '2'; ?>" class="text-center">Пусто</td></tr>
	<?	} ?>
		</table>
	<?php if ($desktop) { echo "</div>"; } ?>
	
	<?php if (false && !$desktop && $paginationOn) { ?>
<!--
	<nav class="text-center">
		<ul class="pagination pagination-lg">
			<li class="disabled"><a href="< ? =CommonFunctions::changeOffsetLink($desktop, $offset-$limit);?>" aria-label="Предыдущая"><span aria-hidden="true">&laquo;</span></a></li>
			<li class="active"><a href="#">1<span class="sr-only"> (текущая)</span></a></li>
			<li><a href="< ? =CommonFunctions::changeOffsetLink($desktop, $offset+$limit);?>">2</a></li>
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