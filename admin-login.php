<?php
	include 'inclueds/core/db_config.php';
	include 'includes/core/CommonFunctions.php';
	
	
	
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Вход в систему</title>
	</head>
	<body class="admin-page-login">

		<div class="container absolute-center-center">
			<div class="glass-panel">
			
				<i class="fa fa-5x fa-wifi margin-bottom"></i>
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
								<input type="checkbox" name="remember-me" id="remember-me"><label for="remember-me">Оставаться в системе</label>
						</div>
				
						<button type="submit" class="btn btn-lg btn-black gradient">Войти <i class="fa fa-sign-in"></i></button>
				
					</form>
				</div>
				
				<div class="margin-top">
					<a href="#" id="forgot-password-button">Забыли пароль?</a>
				</div>
				
			</div>
		</div>
	<?php include 'includes/base/footer.php'; ?>
	<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
	<script>
		
		alert("0");
		
		$(document).ready(function() {
			
			alert("1");
			
			// Сфокусироваться на поле Логин при загрузке страницы
			$("#login").focus();
			setTimeout(function(){
				var login = $("#login");
				if ($(login).value() != '') {
					$("#password").focus();
				}
				alert("2");
			}, 500);
			
			reorderToLogin();
			
			function reorderToLogin() {
				$("#login").css('z-index', '5');
				$("#password").css('z-index', '4');
				alert("4");
			}
			
			function reorderToPassword() {
				$("#login").css('z-index', '4');
				$("#password").css('z-index', '5');
				alert("5");
			}
			
			alert("3");
			
			$("#login").focusin(reorderToLogin);
			
			$("#password").focusin(reorderToPassword);
		});
		
	</script>
	</body>
</html>