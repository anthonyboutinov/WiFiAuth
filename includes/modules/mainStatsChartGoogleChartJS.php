<?php
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
			[{type: 'date', label: 'День'}, {type: 'number', label: 'Вконтакте'}, {type: 'number', label: 'Facebook'}, {type: 'number', label: 'Twitter'}, {type: 'number', label: 'Instagram'}],
			[new Date(2015, 5, 5),	1000,	400,	200,	13],
			[new Date(2015, 5, 6),	1170,	460, 0, 4],
			[new Date(2015, 5, 7),	660,	1120, 200,	13],
			[new Date(2015, 5, 8),	1030,	540, 200,	131],
			[new Date(2015, 5, 8),	1030,	540, 200,	131],
			[new Date(2015, 5, 9),	1170,	461, 50, 40],
			[new Date(2015, 5, 10),	1000,	408,	220,	13],
			[new Date(2015, 5, 11),	1000,	460,	200,	83],
			[new Date(2015, 5, 12),	800,	812,	200,	13],
			[new Date(2015, 5, 13),	1040,	400,	200,	0],
			[new Date(2015, 5, 14),	1000,	799,	200,	13],
			[new Date(2015, 5, 15),	1020,	440,	207,	53],
			[new Date(2015, 5, 16),	600,	450,	220,	93],
		]);

		var options = {
			animation: {duration: 1000, startup: true, easing: 'inAndOut' },
			explorer: {},
			selectionMode: 'single',
			tooltip: {trigger: 'selection'},
			backgroundColor: { fill:'transparent' },
			fontName: 'Fontatigo, "Helvetica Nueue", Helvetica, Arial, "Lucida Grande", sans-serif',
			fontSize: 14,
			colors: <?php echo ("['$chartColor1', '$chartColor2', '$chartColor3', '$chartColor4']"); ?>,
			chartArea: {left:0,top:0,width:'100%',height:'<?=$charthHeight?>'},
			tooltip: {isHtml: true},
			hAxis: {
				textStyle:{color: '#FFF'},
				baselineColor: 'none',
				gridlines: {color: 'none', count: 0},
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