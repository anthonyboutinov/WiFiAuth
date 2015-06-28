<div class="complex-h1">
	<i class="fa fa-line-chart hidden-xs"></i>
	<h1>График авторизаций<span class="hidden-xs"> в&nbsp;сети</span></h1>
	<h2>Количество пользователей за&nbsp;последние 30&nbsp;дней</h2>					
</div>
<div class="page-wrapper chart-wrapper">

	<!--Div that will hold the chart-->
	<div id="line-chart" class="chart"></div>
	
	<div class="position-relative"><div class="hAxis"></div></div>
	
</div>
<!-- Legend -->
<ul class="legend nav<?php if (!$drawFullContent) echo " not-draw-full-content";?>" id="legend">
	<?php
	
	$chartLegendValues = $database->getLoginCountByLoginOption(30); // 30 days
	
	for ($i = 0; $i < $numberOfSocialNetworks; $i++) {
	?>
	<li style="width:<?=(100/$numberOfSocialNetworks);?>%">
		<div class="legend-circle animated zoomIn" style="border-color: <?=$chartColors[$i];?>;"></div>
		<div class="legend-title"><? echo $socialNetworksNames[$i];?></div>
		<div class="legend-last-value" style="color: <?=$chartColors[$i]; ?>;"><?=CommonFunctions::NVL($chartLegendValues[$i]['PERCENTAGE'], 0);?>%</div>
	</li>
	<?php } ?>
</ul>
<!-- EOF Legend -->