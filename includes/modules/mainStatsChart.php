<div class="complex-h1">
	<i class="fa fa-line-chart hidden-xs"></i>
	<h1>График авторизаций в сети</h1>
	<h2>Количество пользователей за&nbsp;последние 30&nbsp;дней</h2>					
</div>
<div class="page-wrapper chart-wrapper">

	<!--Div that will hold the chart-->
	<div id="line-chart" class="chart"></div>
	
	<div class="position-relative"><div class="hAxis"></div></div>
	
</div>

<ul class="legend nav<?php if (!$drawFullContent) echo " not-draw-full-content"; ?>" id="legend">
	<?php
	
	$chartLegendPercentageValues = [40, 36, 15, 9];
	
	for ($i = 0; $i < $numberOfSocialNetworks; $i++) {
	?>
	<li>
		<div class="legend-circle animated zoomIn" style="border-color: <?php echo $chartColors[$i]; ?>;"></div>
		<div class="legend-title"><? echo $socialNetworksNames[$i];?></div>
		<div class="legend-last-value" style="color: <?php echo $chartColors[$i]; ?>;"><?php echo $chartLegendPercentageValues[$i]; ?>%</div>
	</li>
	<?php } ?>
</ul>

<?php if ($drawFullContent) { ?>
<div class="position-relative text-center<?php
	if ($drawFullContent && isset($drawAll) && $drawAll) echo " hidden-sm"; ?>">
	<span class="legend-tip zero-opacity animated" id="legend-tip">
		<i class="fa fa-info-circle"></i> Процент от общего числа посетителей&nbsp;за&nbsp;месяц
	</span>
</div>
<?php } ?>