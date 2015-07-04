<?php
	
	if (isset($_SESSION['main-stats-chart-data-limit'])) {
		$temp = $_SESSION['main-stats-chart-data-limit'];
	} else {
		$temp = 30;
	}
	
	$chartLegendValues = $database->getLoginCountByLoginOption($temp);
	
	
	if (sizeof($chartLegendValues) > 0) {
	
		if ($drawFullContent) {
			$charthHeight = 300;
			$height =  $charthHeight + 30;
		} else {
			$charthHeight = 230;
			$height =  $charthHeight + 30;
		}
	
		include_once('googleChartAPI.html');
?>
<script type="text/javascript">
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {
		var data = new google.visualization.arrayToDataTable([
			[{type: 'date', label: 'День'},
			<?php
				echo CommonFunctions::arrayToString(
					CommonFunctions::extractSingleValueFromMultiValueArray(
						$database->getLoginOptions(), 'NAME'
					),
					false, true, true, ' ', '],', "{type: 'number', label: '", "'}"
				);
				
				if (isset($_SESSION['main-stats-chart-data-offset']) && isset($_SESSION['main-stats-chart-data-limit'])) {
					echo CommonFunctions::arrayToString(
						$database->getMainStatsTable($_SESSION['main-stats-chart-data-limit'], $_SESSION['main-stats-chart-data-offset']), false, false, false
					);
				} else {
					echo CommonFunctions::arrayToString(
						$database->getMainStatsTable(30), false, false, false
					);
				}
				
			?>
		]);

		var options = {
			animation: {duration: 1000, startup: true, easing: 'inAndOut' },
			explorer: {},
			selectionMode: 'single',
			tooltip: {trigger: 'selection'},
			backgroundColor: { fill:'transparent' },
			fontName: 'Fontatigo, "Helvetica Nueue", Helvetica, Arial, "Lucida Grande", sans-serif',
			fontSize: 14,
			colors: <?=CommonFunctions::arrayToString($chartColors);?>,
			chartArea: {left:0,top:0,width:'100%',height:'<?=$charthHeight?>'},
			tooltip: {isHtml: true},
			hAxis: {
				textStyle:{color: '#FFF'},
				baselineColor: 'none',
				gridlines: {color: 'none', count: 0}
			},
			vAxis: {
				textStyle:{color: '#FFF'},
				textPosition: 'in',
				baselineColor: 'none',
				gridlines: {color: '#6991af', count: 0},
				format: '0'
			},
			height: <?=$height?>
			<?php if ($curveMainStatsChart) { echo ",curveType: 'function'"; } ?>
		};

		var chart = new google.visualization.LineChart(document.getElementById('line-chart'));

		chart.draw(data, options);
	}

</script>
<?php } ?>