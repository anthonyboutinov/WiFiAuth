<?php	include 'includes/core/vars.php'; ?><!DOCTYPE html>
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
		<?php if ($desktop) {?><script type="text/javascript" src="includes/js/birthdays.js"></script><?php } ?>
	</body>
</html>