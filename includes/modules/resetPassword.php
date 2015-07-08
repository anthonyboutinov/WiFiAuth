<?php

	$desktop = true;	
	if (isset($_GET["mobile"])) {
		$desktop = false;
	}
	
	$intellectual_view = false;
	if (isset($_COOKIE['birthdays-intellectual-view'])) {
		$intellectual_view = $_COOKIE['birthdays-intellectual-view'];
	} else {
		$_COOKIE['birthdays-intellectual-view'] = $intellectual_view;
	}
	
	$intellectual_view_min_threshold = 1;
	
	$limit = $drawFullContent ? $database->getTablePageLimit() : $database->getDashboardTablePreviewLimit();
	$birthdays = $database->getBirthdays(0, $limit, $intellectual_view);
	
	$dislay_as_flipcard = (CommonMethods::supportsModernCSS() &&  $birthdays->num_rows >= $intellectual_view_min_threshold && (!$drawFullContent || !$desktop));

?>

<?php if ($dislay_as_flipcard) { ?>
<section class="card-container">
  <div id="birthdays-card">
    <figure class="front">
<?php } ?>

<h1 class="flip-birthdays-card<?php if ($birthdays->num_rows >= $intellectual_view_min_threshold) {echo ' link';} ?>">
	<span class="ignore-link-coloring"><i class="fa fa-birthday-cake"></i> Дни рождения</span>
	<span class="options">
		<?php if (!$dislay_as_flipcard) { ?>
		<a href="#" id="intellectual-view-toggle">
			<i class="fa fa-toggle-<?=(($intellectual_view == true) ? 'on' : 'off');?>"></i><span class="hidden-xs"> Умная сортировка</span>
		</a>
		<i class="fa fa-question" id="option-help" data-toggle="tooltip" data-placement="left" title="
			В&nbsp;таком представлении записи сортируются в&nbsp;соответсвтии с&nbsp;уровнем лояльности клиентов и&nbsp;близости их&nbsp;дня&nbsp;рождения.
		"></i>
		<?php } else if ($birthdays->num_rows >= $intellectual_view_min_threshold) { ?><i class="fa fa-cogs"></i><?php } ?>
	</span>
</h1>
<div class="page-wrapper">
	
	<?php if ($desktop) { 	?><table class="table table-head-row text-center" id="birthdays-header"><?php } 
		  else { 			?><table class="table text-center contains-fas td-vertical-align"><?php } ?>
		<?php if ($drawFullContent) { ?><tr class="head-row">
			<td><span class="sr-only">Логин</span></td>
			<td>Клиент</td>
			<?php if ($drawFullContent) { ?><td>День рождения</td><?php } ?>
			<?php if ($desktop) { 	?>
			<td>Через <span class="hidden-xs hidden-sm">(дней)</span></td>
			<td>Исполнится</td>
			<?php } ?>
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
				$years_leftover_second = $row['DAYS_UNTIL'] % 100  > 10 && $row['DAYS_UNTIL'] % 100 < 15;
				if ($desktop) {
					$russian_days_ending = (
						$years_leftover == 1 ? 'день' : (
							($years_leftover > 1 && $years_leftover < 5) && !$years_leftover_second ? 'дня' : 'дней'
						)
					);
				} else {
					$russian_days_ending = 'д';
				}
				
				$will_turn_leftover = $row['WILL_TURN'] % 10;
				$will_turn_leftover_second = $row['WILL_TURN'] % 100  > 10 && $row['WILL_TURN'] % 100 < 15;
				$russian_years_ending = (
					$will_turn_leftover == 1 ? ($desktop ? 'год' : 'г') : (
						($will_turn_leftover > 1 && $will_turn_leftover < 5) && !$will_turn_leftover_second ? ($desktop ? 'года' : 'г') : ($desktop ? 'лет' : 'л')
					)
				);
				
				
				$days_until = (
					$row['DAYS_UNTIL'] == 0 ? 'сегодня' : (
						$row['DAYS_UNTIL'] == 1 ? 'завтра' :  $row['DAYS_UNTIL']
					)
				);
				
				$days_until_formatted = $row['DAYS_UNTIL'];
				
				$days_until_tooltip = (	
					$row['DAYS_UNTIL'] == 0 ? 'сегодня' : (
						$row['DAYS_UNTIL'] == 1 ? 'завтра' :  (
							$desktop ?
								'через ' :
								'<small class="x2">через</small> '
						).$days_until_formatted.' '.$russian_days_ending
					)
				);
	?>

			<tr>
				<td class="text-center">
					<span class="fa-stack"><i class="fa fa-<?=$row['LOGIN_OPTION_SHORT_NAME'];?>"></i></span>
					<span class="sr-only"><?=$row['LOGIN_OPTION_NAME'];?></span>
				</td>
				<td class="text-left"><a href="<?=$row['LINK'];?>" target="blank"><?php
					if ($desktop) {
						echo $row['NAME'];
					} else {
						$exploded_name = explode(' ',trim($row['NAME']));
						echo $exploded_name[0].'<br>'.$exploded_name[1];
					}
					?></a></td>
				<?php if ($drawFullContent) { ?><td class="text-<?=$desktop ? 'right' : 'right';?>">
					<?php
						echo $row['BIRTHDAY'];
						if (!$desktop) { 
							echo "<br>".$row['WILL_TURN']." $russian_years_ending ";
							if ($row['DAYS_UNTIL'] < 5) {
								echo '<span class="alert-value alert-value-'.$row['DAYS_UNTIL'].'">';
							}
							echo $days_until_tooltip;
							if ($row['DAYS_UNTIL'] < 5) {
								echo '</span>';
							}
						}
					?>
				</td><?php } ?>
				<?php if ($desktop) { ?>
					<td class="text-right"><span data-toggle="tooltip" data-placement="left" title="День рождения <?=$days_until_tooltip;?>"<?php
						if ($row['DAYS_UNTIL'] < 5) {
							echo ' class="alert-value alert-value-'.$row['DAYS_UNTIL'].'"';
						}
					?>><?=$days_until;?></span></td>
					<td class="text-right"><span data-toggle="tooltip" data-placement="left" title="Исполнится <?=$row['WILL_TURN'].' '.$russian_years_ending;?>"><?=$row['WILL_TURN'];?></span></td>
				<?php } ?>
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


<?php if ($dislay_as_flipcard) { ?>
	</figure>
    <figure class="back">
	    <h1 class="link flip-birthdays-card">
			<span class="ignore-link-coloring"><i class="fa fa-birthday-cake"></i> Дни рождения</span>
			<span class="options">
				<i class="fa fa-chevron-circle-left"></i>
			</span>
		</h1>
		<div class="page-wrapper">
			<div class="margin-bottom margin-top text-center lead">
				<a href="#" id="intellectual-view-toggle">
			    	<i class="fa fa-toggle-<?=(($intellectual_view == true) ? 'on' : 'off');?>"></i> Умная сортировка
				</a>
			</div>
	    	<p>В&nbsp;представлении «Умная сортировка» записи сортируются в&nbsp;соответсвтии с&nbsp;уровнем лояльности клиентов и&nbsp;близости их&nbsp;дня&nbsp;рождения.</p>
		</div>
    </figure>
  </div>
</section>
<?php } ?>