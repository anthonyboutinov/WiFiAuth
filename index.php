<?php require 'includes/core/Notification.php'; ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Re[Spot]</title>
	</head>
	<body class="index-page"><div class="background-cover"></div>
		<div class="container glass-panel">
			
			<nav class="navbar">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Навигация</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
<!-- 						<span class="navbar-brand">Re[Spot]</span> -->
					</div>
			
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="admin-login.php"><i class="fa fa-fw fa-sign-in"></i> Личный кабинет</a></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>

			<div class="glass-panel">
			<h1>
				<i class="fa fa-wifi"></i> Re[Spot]
			</h1>
			<h2>
				Скоро здесь будет сайт
			</h2>
			<div class="page-wrapper">
				<!-- Текст здесь -->
			</div>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script>

/// Подготовиться к вертикальному позиционированию
function prepareForVerticalPositioning() {
	panel = $("body > .glass-panel");
	secondPanel = $("body > .glass-panel .glass-panel");
	navbar = $("nav.navbar");
}

/// Выполнить вертикальное позиционирование
function positionVertically() {	
	$(panel).css('height', $(document).height());
	$(secondPanel).css('height', $(panel).outerHeight() - $(navbar).outerHeight() - 60);
}
			
/// Включить вертикальное позиционирование
function enableVerticalPositioning() {
	setTimeout(positionVertically, 200);
	$(window).resize(positionVertically);
}

$(document).ready(function() {	
	prepareForVerticalPositioning();
	enableVerticalPositioning();
});
			
		</script>
	</body>
</html>