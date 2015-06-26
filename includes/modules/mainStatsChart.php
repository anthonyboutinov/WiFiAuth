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

<?php include('includes/modules/chartLegend.php'); ?>

<div class="position-relative text-center<?php
	if ($drawFullContent && isset($drawAll) && $drawAll) echo " hidden-sm"; ?>">
	<span class="legend-tip zero-opacity animated" id="legend-tip">
		<i class="fa fa-info-circle"></i> Процент от общего числа посетителей&nbsp;за&nbsp;месяц
	</span>
</div>