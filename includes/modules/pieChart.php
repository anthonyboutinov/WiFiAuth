
<h1>
	<i class="fa fa-pie-chart"></i>
	Соотношение<?php if ($drawFullContent) { ?><span class="hidden-xxs hidden-md"> посетителей</span><?php }
		else { ?><span class="hidden-xxs visible-xs-inline"> посетителей</span><?php } ?>
</h1>
<div class="page-wrapper<?php if ($drawFullContent) echo " chart-wrapper-in-xs"; ?>">

	<!--Div that will hold the chart-->
	<div id="pie-chart" class="chart"></div>
	
</div>


<?php if (isset($drawTableAndPieChart) && isset($drawAll) && !$drawAll) { include('includes/modules/chartLegend.php'); } ?>