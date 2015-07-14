<!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Новый пароль — Re[Spot]</title>
	</head>
	<body class="admin-page-login"><div class="background-cover"></div>

		<div class="container absolute-center-center">
			<div class="glass-panel">
			
				<i class="fa fa-5x fa-wifi margin-bottom"></i>
				<div id="main-area">
					
					<h1>Новый пароль</h1>
					<div class="form">
						<form method="post">
							<input type="hidden" name="form-name" value="password-reset-set-new">
							<input type="hidden" name="LOGIN" value="<?=$_GET['l'];?>">
							<input type="hidden" name="PASSWORD_RESET_TOKEN" value="<?=$_GET['t'];?>">
							
							<p>Задайте новый пароль для <?=$_GET['l'];?></p>
					
							<div class="form-group vertically-concatinated top">
								<label class="sr-only" for="password">Новый пароль</label>
								<input type="password" class="form-control position-relative" id="password" name="password" placeholder="Новый пароль">
							</div>
					
							<div class="form-group vertically-concatinated bottom">
								<label class="sr-only" for="password">Новый пароль еще раз</label>
								<input type="password" class="form-control position-relative" id="repeat-password" name="repeat-password" placeholder="Новый пароль еще раз">
							</div>
					
							<button type="submit" class="btn btn-lg btn-black gradient">Продолжить <i class="fa fa-chevron-right"></i></button>
					
						</form>
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