$( document ).ready(function() {
		
	/* *
	   * Выборка
	 */
	 
	function changeMainStatsChartPeriod(val) {
		$.ajax({
			type: "POST",
			url: "admin-query.php",
			data: {
				'form-name': 'get-main-stats-chart-data',
				'offset': 0,
				'limit': val
			},
			success: function(msg){
				location.reload();
			},
			fail: failNotification
		});
	}
	 
	$("#main-stats-chart-period").change(function() {
		changeMainStatsChartPeriod($(this).val());
	});
	$("#main-stats-chart-period_xs").change(function() {
		changeMainStatsChartPeriod($(this).val());
	});
	
});

$(window).resize(drawChart);