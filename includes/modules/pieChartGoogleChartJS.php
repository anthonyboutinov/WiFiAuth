<?php
if ($drawFullContent) {
	$charthHeight = 200;
	$height =  $charthHeight + 30;
} else {
	$charthHeight = 230;
	$height =  $charthHeight + 30;
}

include_once('googleChartAPI.html');
?>
<!--Load the AJAX API-->
<!--
<script type="text/javascript"
			src="https://www.google.com/jsapi?autoload={
				'modules':[{
					'name':'visualization',
					'version':'1',
					'packages':['corechart'],
					'language': 'ru'
				}]
			}"></script>
-->
<script type="text/javascript">
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawPieChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawPieChart() {
		var data = new google.visualization.arrayToDataTable([
			['Соцсеть', 'Количество посетителей за последние 30 дней'],
			<?=CommonFunctions::arrayToJSONString(
				CommonFunctions::extractSingleValueFromMultiValueArray(
					$database->getLoginCountByLoginOption(30), 'LOGIN_COUNT', 'NAME'
				)
			);?>
			/*
['ВКонтакте', 10340],
			['Facebook', 4049],
			['Twitter', 2945],
			['Instagram', 1905]
*/
		]);

		var options = {
			animation: {duration: 1000, startup: true, easing: 'inAndOut' },
			tooltip: {trigger: 'selection'},
			backgroundColor: { fill:'transparent' },
			fontName: 'Fontatigo, "Helvetica Nueue", Helvetica, Arial, "Lucida Grande", sans-serif',
			pieSliceTextStyle: {color: '#333'},
			fontSize: 14,
			colors: <?=CommonFunctions::arrayToString($chartColors, false, '[', ']', '\'');?>,
			chartArea: {left:10,top:10,width:'90%',height:'<?=$charthHeight?>'},
			tooltip: {isHtml: true, showColorCode: true},
			height: <?=$height?>,
			legend: {position: 'top'},
			is3D: true,
			pieSliceText: 'value'
		};

		var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));

		chart.draw(data, options);
	}

</script>