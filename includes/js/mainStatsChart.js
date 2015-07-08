$( document ).ready(function() {
		
	/* *
	   * Выборка
	 */
	$("#main-stats-chart-period").change(function() {
		$.ajax({
			type: "POST",
			url: "admin-query.php",
			data: {
				'form-name': 'get-main-stats-chart-data',
				'offset': 0,
				'limit': $(this).val()
			},
			success: function(msg){
				location.reload();
			},
			fail: failNotification
		});
	});
	
});