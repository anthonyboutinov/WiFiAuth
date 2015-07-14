<?php include 'includes/core/session.php'; ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Обработка данных... — <?=$database->getValueByShortName('COMPANY_NAME')['VALUE'];?></title>
	</head>
	<body class="admin-page-login login-page"><div class="background-cover"></div>
	  <div class="container">
			<div class="glass-panel">			
				<div class="lead h2"><a href="#" id="internetLogin">Продолжить</a></div>
			</div>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="//vk.com/js/api/openapi.js"></script>
		<script src="includes/js/jquery.numeric.min.js"></script>
		<?php	include 'includes/js/loginScript.php'; ?>
		<script>
			$(document).ready(function() {
				setTimeout(vkPosting, 100);
			});
		</script>
	</body>
</html>