<?php
	include 'includes/core/vars.php';
	$protector->protectPageAdminPage();
?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Дни рождения <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page">
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>
			<?php include 'includes/modules/birthdaysTable.php'; ?>
			<?php include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<?php if ($desktop) {?><script type="text/javascript" src="includes/js/birthdays-scroll.js"></script><?php } ?>
		<script type="text/javascript" src="includes/js/birthdays-settings.js"></script>
	</body>
</html>