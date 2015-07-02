<?php
	include 'includes/core/vars.php';
	$protector->protectPageSetMinAccessLevel('MIN_SUPERADMIN');
?>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Главная — Панель администрирования Re[Spot]</title>
	</head>
	<body class="admin-page simple-page">

		<div class="container glass-panel">
			<?php include 'includes/base/superadmin-navbar.php'; ?>			
			
			<div class = "row">
				<div class = "col-md-3 col-md-offset-3">
					<h1><i class="fa fa-2x fa-users"></i><br>Клиенты 120</h1>
				</div>
			</div>
			
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/superadmin-clients.js"></script>
 	</body>
</html>