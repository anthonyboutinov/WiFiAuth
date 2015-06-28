<?php

	$desktop = true;	
	if (isset($_GET["mobile"])) {
		$desktop = false;
	}
	
	$intellectual_view = 1;
	if (isset($_COOKIE['birthdays-intellectual-view'])) {
		$intellectual_view = $_COOKIE['birthdays-intellectual-view'];
	} else {
		$_COOKIE['birthdays-intellectual-view'] = $intellectual_view;
	}
	
	$limit = $drawFullContent ? $database->tablePageLimit : $database->dashboardTablePreviewLimit;
	$birthdays = $database->getBirthdays(0, $limit, $intellectual_view);
	

?>
<!-- <script>var intellectual_view = < ?=$intellectual_view;?>;</script> -->
<h1>
	<i class="fa fa-birthday-cake"></i> Дни рождения
	<span class="options">
		<a href="#" id="intellectual-view-toggle">
			<i class="fa fa-toggle-<?=$intellectual_view == 1 ? 'on' : 'off';?>"></i> Умная сортировка
		</a>
		<i class="fa fa-question button" data-toggle="tooltip" data-placement="left" title="
			В таком представлении записи сортируются в&nbsp;соответсвтии с&nbsp;уровнем лояльности клиентов и&nbsp;близости их&nbsp;дня&nbsp;рождения.
		"></i>
	</span>
</h1>
<div class="page-wrapper">
	
	<?php if ($desktop) { 	?><table class="table table-head-row text-center" id="birthdays-header"><?php } 
		  else { 			?><table class="table text-center contains-fas"><?php } ?>
		<?php if ($drawFullContent) { ?><tr class="head-row">
			<td><span class="sr-only">Логин</span></td>
			<td>Клиент</td>
			<?php if ($drawFullContent) { ?><td>День рождения</td><?php } ?>
			<td>Через <span class="hidden-xs hidden-sm">(дней)</span></td>
			<td>Исполнится</td>
		</tr>
	<?php }
		if ($desktop) { ?>
	</table>
	<div class="scrollable gradient" id="birthdays-scrollable">
		<table class="table text-center contains-fas">
	<?php
		}
		
		if ($birthdays->num_rows > 0) {
			$i = 0;
			while($row = $birthdays->fetch_assoc()) {
				$i++;
				
				
				$years_leftover = $row['DAYS_UNTIL'] % 10;
				$russian_days_ending = (
					$years_leftover == 1 ? 'день' : (
						$years_leftover > 1 && $years_leftover < 5 ? 'дня' : 'дней'
					)
				);
				
				$will_turn_leftover = $row['WILL_TURN'] % 10;
				$russian_years_ending = (
					$will_turn_leftover == 1 ? 'год' : (
						$will_turn_leftover > 1 && $will_turn_leftover < 5 ? 'года' : 'лет'
					)
				);
				
				
				$days_until = (
					$row['DAYS_UNTIL'] == 0 ? 'сегодня' : (
						$row['DAYS_UNTIL'] == 1 ? 'завтра' :  $row['DAYS_UNTIL']
					)
				);
				
				$days_until_tooltip = (
					$row['DAYS_UNTIL'] == 0 ? 'сегодня' : (
						$row['DAYS_UNTIL'] == 1 ? 'завтра' :  'через '.$row['DAYS_UNTIL'].' '.$russian_days_ending
					)
				);
				
				
	?>

			<tr>
				<td class="text-center">
					<span class="fa-stack"><i class="fa fa-<?=$row['LOGIN_OPTION_SHORT_NAME'];?>"></i></span>
					<span class="sr-only"><?=$row['LOGIN_OPTION_NAME'];?></span>
				</td>
				<td class="text-left"><a href="<?=$row['LINK'];?>" target="blank"><?=$row['NAME'];?></a></td>
				<?php if ($drawFullContent) { ?><td class="text-right"><?=$row['BIRTHDAY'];?></td><?php } ?>
				<td class="text-right"><span data-toggle="tooltip" data-placement="left" title="День рождения <?=$days_until_tooltip;?>"<?php //<?=(($row['DAYS_UNTIL'] == 0) ? 'text-center' : 'text-right');
					if ($row['DAYS_UNTIL'] < 5) {
						echo ' class="alert-value alert-value-'.$row['DAYS_UNTIL'].'"';
					}
				?>><?=$days_until;?></span></td>
				<td class="text-right"><span data-toggle="tooltip" data-placement="left" title="Исполнится <?=$row['WILL_TURN'].' '.$russian_years_ending;?>"><?=$row['WILL_TURN'];?></span></td>
			</tr>
	<?php 
			}
		} else { ?>
			<tr><td colspan="<?php if ($drawFullContent) echo '4'; else echo '3'; ?>" class="text-center">Пусто</td></tr>
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