<?php	
	include 'includes/core/session.php';
	
	if (isset($_POST['form-name'])) {
		
		// Экран восстановления пароля
		if ($_POST['form-name'] == 'forget-password-recovery-screen') { ?>
			
			<h1>Восстановление пароля</h1>
			<div class="form">
				<form method="post">
					<input type="hidden" name="form-name" value="forget-password-recovery">
			
					<div class="form-group">
						<label for="login">Логин или email</label>
						<input type="text" class="form-control position-relative" id="login" name="login" placeholder="Логин или email" value="<?=$_POST['login'];?>">
					</div>
					
					<p>Вам будет выслано письмо на ваш email со ссылкой на страницу, на которой вы сможете задать новый пароль.</p>
			
					<button type="submit" class="btn btn-lg btn-black gradient">Продолжить <i class="fa fa-chevron-right"></i></button>
			
				</form>
			</div>
			
			<?php exit();
		}
		// Обработка формы восстановления пароля
		else if ($_POST['form-name'] == 'forget-password-recovery') {
			
			if (!isset($_POST['login'])) {
				die('DEBUG Error: no form data!');
			}
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
				
				</div>
				
			</div>
		</div>
	<?php include 'includes/base/footer.php'; ?>
	<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
	<script type="text/javascript" src="includes/js/admin-login.js"></script>
	</body>
</html>