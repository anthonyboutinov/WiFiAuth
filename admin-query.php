<?php 
	include 'includes/core/session.php';
	
	if (isset($_POST['form-name'])) {
	
		// Изменение периода выборки главного графика статистики
		if ($_POST['form-name'] == 'get-main-stats-chart-data') {
		
			$_SESSION['main-stats-chart-data-offset'] = $_POST['offset'];
			$_SESSION['main-stats-chart-data-limit'] = $_POST['limit'];
		
		}	
	} else if (isset($_GET['form-name'])) {
		
		if ($_GET['form-name'] == 'revert-history' && isset($_GET['ID_VAR'])) {

			$database->revertOldVarValue($_GET['ID_VAR']);

		}

	}
?>
