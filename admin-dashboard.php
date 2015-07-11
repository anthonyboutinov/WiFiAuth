<?php
	include 'includes/base/admin.php';
	$database->prepareForDashboardTableQueries();
		
	// Цвета графиков (и текста в таблицах)
	$chartColors = $database->getColors();
	
	// Сглаживать ли график
	$curveMainStatsChart = $database->getValueByShortName('CURVE_CHARTS')['VALUE'];
	
	// Отрисовывать мини-версии модулей
	$drawFullContent = false;
	
	// Не предоставлять возможность пролистывать таблицы дальше того, что загружено в первом SQL запросе
	$paginationOn = false;
	
?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Главная <?php echo $adminPanelTitle; ?></title>
		<?php include 'includes/modules/mainStatsChartGoogleChartJS.php'; ?>
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
				
				<div class="col-md-4 col-sm-12 col-md-push-8">
					
					<!-- Clients Table -->
					<?php include 'includes/modules/clientsTable.php'; ?>
					<a class="hidden-xs hidden-sm dashboard-more-info animated fadeInUp" href="admin-users-combined-list.php">Подробнее <i class="fa fa-chevron-circle-right"></i></a>
					<a class="visible-xs-block visible-sm-block dashboard-more-info animated fadeInUp" href="admin-users-combined-list.php?onlyclients">Подробнее <i class="fa fa-chevron-circle-right"></i></a>
					
				</div>
				
				<div class="col-md-4 col-sm-12">
					
					<!-- Birthdays Table -->
					<?php include 'includes/modules/birthdaysTable.php'; ?>
					<a class="hidden-xs dashboard-more-info animated fadeInUp" href="admin-birthdays-list.php">Подробнее <i class="fa fa-chevron-circle-right"></i></a>
					<a class="visible-xs-block dashboard-more-info animated fadeInUp" href="admin-birthdays-list.php?mobile">Подробнее <i class="fa fa-chevron-circle-right"></i></a>
				</div>
				
				<div class="col-md-4 col-sm-12 col-md-pull-8">
					
					<!-- Loyal Clients Table -->	
					<?php include 'includes/modules/loyalClientsTable.php'; ?>
					<a class="hidden-xs hidden-sm dashboard-more-info animated fadeInUp" href="admin-users-combined-list.php">Подробнее <i class="fa fa-chevron-circle-right"></i></a>
					<a class="visible-xs-block visible-sm-block dashboard-more-info animated fadeInUp" href="admin-users-combined-list.php?onlyloyals">Подробнее <i class="fa fa-chevron-circle-right"></i></a>

										
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
		<script type="text/javascript" src="includes/js/birthdays-settings.js"></script>
		
	</body>
</html>