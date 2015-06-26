
<h1>
	<i class="fa fa-pie-chart"></i>
	Соотношение<?php if ($drawFullContent) { ?><span class="hidden-xxs hidden-md"> посетителей</span><?php }
		else { ?><span class="hidden-xxs visible-xs-inline"> посетителей</span><?php } ?>
</h1>
<div class="page-wrapper<?php if ($drawFullContent) echo " chart-wrapper-in-xs"; ?>">

	<!--Div that will hold the chart-->
	<div id="pie-chart" class="chart"></div>
	
</div>


<?php if (isset($drawTableAndPieChart) && isset($drawAll) && !$drawAll) { ?>
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
<?php } ?>