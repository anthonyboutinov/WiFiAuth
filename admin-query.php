<?php 
	include 'includes/core/session.php';
	
	if (isset($_POST['form-name'])) {
	
		// Изменение периода выборки главного графика статистики
		if ($_POST['form-name'] == 'get-main-stats-chart-data') {
		
			$_SESSION['main-stats-chart-data-offset'] = $_POST['offset'];
			$_SESSION['main-stats-chart-data-limit'] = $_POST['limit'];
		
		}
	
	}
?>
