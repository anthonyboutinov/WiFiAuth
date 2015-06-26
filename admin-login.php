<?php	include 'includes/core/vars.php'; ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Авторизация <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page-login">

		<div class="container absolute-center-center">
			<div class="glass-panel">
			
				<h1>Вход в систему</h1>
				<div class="form">
					<form>
				
						<div class="form-group vertically-concatinated top">
							<label class="sr-only" for="login">Логин</label>
							<input type="text" class="form-control position-relative" id="login" placeholder="Логин">
						</div>
				
						<div class="form-group vertically-concatinated bottom">
							<label class="sr-only" for="password">Пароль</label>
							<input type="password" class="form-control position-relative" id="password" placeholder="Пароль">
						</div>
				
						<div class="checkbox">
							<label>
								<input type="checkbox" id="remember-me"> Оставаться в системе
							</label>
						</div>
				
						<button type="submit" class="btn btn-lg btn-black gradient">Войти <i class="fa fa-sign-in"></i></button>
				
					</form>
				</div>
				
			</div>
		</div>
	<?php include 'includes/base/footer.php'; ?>
	<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
	<script>
		
		$(document).ready(function() {
			
			// Сфокусироваться на поле Логин при загрузке страницы
			$( "#login" ).focus();
			reorderToLogin();
			
			function reorderToLogin() {
				$("#login").css('z-index', '5');
				$("#password").css('z-index', '4');
			}
			
			function reorderToPassword() {
				$("#login").css('z-index', '4');
				$("#password").css('z-index', '5');
			}
			
			$("#login").focusin(reorderToLogin);
			
			$("#password").focusin(reorderToPassword);
		});
		
	</script>
	</body>
</html>