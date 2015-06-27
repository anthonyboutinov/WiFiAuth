<?php include 'includes/core/vars.php';
	
	// Отрисовывать мини-версии модулей
	$drawFullContent = false;
	
	// Не предоставлять возможность пролистывать таблицы дальше того, что загружено в первом SQL запросе
	$paginationOn = false;
	
?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Главная <?php echo $adminPanelTitle; ?></title>
		<?php
			include 'includes/modules/mainStatsChartGoogleChartJS.php';
			include 'includes/modules/pieChartGoogleChartJS.php';
		?>
	</head>
	<body class="admin-page simple-page dashboard">
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>
			
			<div class="row">
				<div class="col-md-3 col-sm-12 col-md-push-9">
					
					<!-- Main Stats Table -->
					<?php include 'includes/modules/mainStatsTable.php'; ?>
										
				</div>
				
				<div class="col-md-9 col-sm-12 col-md-pull-3">
					
					<!-- Main Stats Chart -->
					<?php include 'includes/modules/mainStatsChart.php'; ?>
					
				</div>

			</div>
			<div class="row">
				
				<div class="col-md-4 col-sm-6">
					
					<!-- Loyal Clients Table -->	
					<?php include 'includes/modules/loyalClientsTable.php'; ?>
					<a class="hidden-xs hidden-sm dashboard-more-info" href="admin-users-combined-list.php">Показать всех <i class="fa fa-chevron-circle-right"></i></a>
					<a class="visible-xs-block visible-sm-block dashboard-more-info" href="admin-users-combined-list.php?onlyloyals">Показать всех <i class="fa fa-chevron-circle-right"></i></a>
					
				</div>
				
				<div class="col-md-4 col-sm-6">
					
					<!-- Birthdays Table -->
					<?php include 'includes/modules/birthdaysTable.php'; ?>
					<a class="dashboard-more-info" href="admin-birthdays-list.php">Показать всех <i class="fa fa-chevron-circle-right"></i></a>					
				</div>
				
				<div class="col-md-4 col-sm-12">
					
					<!-- Clients Table -->
					<?php include 'includes/modules/clientsTable.php'; ?>
					<a class="hidden-xs hidden-sm dashboard-more-info" href="admin-users-combined-list.php">Показать всех <i class="fa fa-chevron-circle-right"></i></a>
					<a class="visible-xs-block visible-sm-block dashboard-more-info" href="admin-users-combined-list.php?onlyclients">Показать всех <i class="fa fa-chevron-circle-right"></i></a>
					
				</div>
				
			</div>
			
			<?php include 'includes/base/footer.php'; ?>
		</div>
		
		<?php
			include 'includes/base/jqueryAndBootstrapScripts.html'; 
		?>
		<script type="text/javascript" src="includes/js/jquery.monthpicker.min.js"></script>
		<script type="text/javascript" src="includes/js/jquery-scrolltofixed-minmaxheightadded-min.js"></script>
		<script type="text/javascript" src="includes/js/mainStatsChart.js"></script>
		
	</body>
</html>