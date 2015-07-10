<!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Вход в систему — Re[Spot]</title>
	</head>
	<body class="admin-page-login">

		<div class="container absolute-center-center">
			<div class="glass-panel">
			
				<i class="fa fa-5x fa-wifi margin-bottom"></i>
				<div id="main-area">
					
					<h1>Вход в систему</h1>
					<div class="form">
						<form method="post">
							<input type="hidden" name="form-name" value="login">
												
							<div class="form-group vertically-concatinated top">
								<label class="sr-only" for="login">Логин</label>
								<input type="text" class="form-control position-relative" id="login" name="login" placeholder="Логин" value="<?=isset($_GET['l']) ? $_GET['l'] : (isset($_COOKIE['remember_me']) ? $_COOKIE['remember_me'] : '');?>">
							</div>
					
							<div class="form-group vertically-concatinated bottom">
								<label class="sr-only" for="password">Пароль</label>
								<input type="password" class="form-control position-relative" id="password" name="password" placeholder="Пароль">
							</div>
					
							<div class="checkbox">
									<input type="checkbox" name="remember-me" id="remember-me" name="remember-me" value="1"<?=isset($_COOKIE['remember_me']) ? ' checked' : '';?>><label for="remember-me">Запомнить меня</label>
							</div>
					
							<button type="submit" class="btn btn-lg btn-black gradient">Войти <i class="fa fa-sign-in"></i></button>
					
						</form>
					</div>
					
					<div class="margin-top">
						<a href="#" id="forget-password-recovery-button">Забыли пароль?</a>
					</div>
				
				</div>
				
			</div>
		</div>
	<?php include 'includes/base/footer.php'; ?>
	<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
	<script type="text/javascript" src="includes/js/jquery.alphanum.js"></script>
	<script type="text/javascript" src="includes/js/admin-login.js"></script>
	</body>
</html>