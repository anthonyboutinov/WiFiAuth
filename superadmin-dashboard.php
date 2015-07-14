<?php
	include 'includes/core/session.php';
// 	$protector->protectPageSetMinAccessLevel('MIN_SUPERADMIN');

	$db_users_count = $database->getDBUsersCount();
?>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Главная — Панель администрирования Re[Spot]</title>
	</head>
	<body class="admin-page simple-page"><div class="background-cover"></div>

		<div class="container glass-panel">
			<?php include 'includes/base/superadmin-navbar.php'; ?>	
			
			<h1 class="huge-cover"><i class="fa fa-dashboard"></i> Панель администрирования</h1>
			
			<div class="page-wrapper">
				<div class="row">
					<div class="col-md-<?=$database->meetsAccessLevel('ROOT') ? 3 : 6;?> col-md-offset-3">
						<a href="superadmin-clients.php">
							<h1 class="dashboard-tile"><i class="fa fa-2x fa-users"></i><br><br>Клиенты <?=$db_users_count[0];?></h1>
						</a>
					</div>
					
					<?php if ($database->meetsAccessLevel('ROOT')) { ?>
					<div class="col-md-3">
						<a href="superadmin-admins.php">
							<h1 class="dashboard-tile"><i class="fa fa-2x fa-users"></i><br><br>Админы <?=$db_users_count[1];?></h1>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<?php include 'includes/js/superadmin.php'; ?>
 	</body>
</html>