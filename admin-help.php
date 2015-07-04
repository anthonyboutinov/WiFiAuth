<?php include 'includes/base/admin.php'; ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Название страницы <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page">
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>

			<h1><i class="fa fa-fw fa-support"></i> Помощь</h1>
			<div class="page-wrapper">
				
				<h2>1. Заголовок</h2>
				
				<h3>1.1. Подзаголовок</h3>
				
				<p class="lead">Ведущий параграф текста</p>
				
				<p>Параграф текста.</p>
				
				<p>Более подробную информацию по маркировке см. здесь: <a href="http://getbootstrap.com/css/#type" target="_blank">http://getbootstrap.com/css/#type</a>.</p>
				
				
			</div>
			<?php	include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
	</body>
</html>