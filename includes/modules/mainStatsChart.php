<?php
	$desktop = true;	
	if (isset($_GET["mobile"])) {
		$desktop = false;
	}
	
	if (sizeof($chartLegendValues) == 0) { ?>
		<h1><i class="fa fa-line-chart hidden-xs"></i> График авторизаций<span class="hidden-xs"> в&nbsp;сети</span></h1>
		<div class="page-wrapper">
			<p class="text-center">Нет данных</p>
		</div>
	<?php
	} else {
		
		// Соц. сети и их названия
		$loginOptions = $database->getLoginOptions();
		$socialNetworksNames = CommonFunctions::extractSingleValueFromMultiValueArray($loginOptions, 'NAME');
		
?>
<div class="complex-h1">
	<i class="fa fa-line-chart hidden-xs"></i>
	<h1>График авторизаций<span class="hidden-xs"> в&nbsp;сети</span></h1>
	<h2>Количество пользователей за<?php if ($desktop) { ?>&nbsp;последние <?=$temp;?>&nbsp;дней</h2><?php } else { echo ' ';} ?>
	<?php if ($desktop) { ?><span class="options"><?php } ?>
		<select id="main-stats-chart-period">
			<option value="365"<?php if ($temp == 365) {echo ' selected';} ?>>1 год</option>
			<option value="183"<?php if ($temp == 183) {echo ' selected';} ?>>6 месяцев</option>
			<option value="92"<?php if ($temp == 92) {echo ' selected';} ?>>3 месяца</option>
			<option value="30"<?php if ($temp == 30) {echo ' selected';} ?>>1 месяц</option>
			<option value="14"<?php if ($temp == 14) {echo ' selected';} ?>>2 недели</option>
		</select>
	<?php if ($desktop) { ?></span><?php } else {echo '</h2>';} ?>
</div>
<div class="page-wrapper chart-wrapper">

	<!--Div that will hold the chart-->
	<div id="line-chart" class="chart"></div>
	
	<div class="position-relative"><div class="hAxis"></div></div>
	
</div>
<!-- Legend -->
<ul class="legend nav<?php if (!$drawFullContent) echo " not-draw-full-content";?>" id="legend">
	<?php
	for ($i = 0; $i < sizeof($chartLegendValues); $i++) {
	?>
	<li style="width:<?=(100/sizeof($chartLegendValues));?>%">
		<div class="legend-circle animated zoomIn" style="border-color: <?=$chartColors[$i];?>;"></div>
		<div class="legend-title"><? echo $socialNetworksNames[$i];?></div>
		<div class="legend-last-value" style="color: <?=$chartColors[$i]; ?>;">
			<?=CommonFunctions::NVL($chartLegendValues[$i]['LOGIN_COUNT'], 0);?>
			(<?=CommonFunctions::NVL($chartLegendValues[$i]['PERCENTAGE'], 0);?>%)
		</div>
	</li>
	<?php } ?>
</ul>
<!-- EOF Legend -->
<?php } ?>