<?php	include 'includes/core/vars.php'; ?><!DOCTYPE html>

<?php	
   $post = $database->getValuesForParentByShortName('POST');
	
	// Заголовок поста
	$postTitle = $post['POST_TITLE']['VALUE'];
	
	// Содержание текста для постов
	$postContent = $post['POST_TEXT']['VALUE'];
	
	//Ссылки на изображения для постов
	$photoVK = $post['POST_IMAGE_VK']['VALUE'];
	$photoFB = $post['POST_IMAGE_FB']['VALUE'];
	
	//Ссылки на страницы клиентов
	$linkVK = $post['POST_LINK_VK']['VALUE'];
	$linkFB = $post['POST_LINK_FB']['VALUE'];
	?>

<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Добро пожаловать! — <?=$companyName;?></title>
	</head>
	<body class="admin-page-login login-page">
		
		<div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-black">
				<form>
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label class="sr-only" for="password">Пароль</label>
								<input type="password" class="form-control position-relative" id="password" placeholder="Пароль">
							</div>
						</div>
						<div class="modal-footer">
							<a href="#" type="submit" class="btn btn-black gradient" id ="passwordButton" >Войти <i class="fa fa-sign-in"></i></a>
						</div>
					</div>
				</form>
			</div>
		</div>

        <div class="modal fade" id="ModalFacebook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h2 class="modal-title" id="myModalLabel">Размещение записи</h2>
					</div>
					<div class="modal-body">
						<img src="<?php echo $photoFB; ?>" class="modal-image">
						<h2><?php echo $postTitle; ?></h2>
						<textarea  class="form-control" rows=5 id="FBTextArea"><?php echo $postContent; ?></textarea>
					</div>
					<div class="modal-footer">
						 <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
						 <button href="#" type="button" class="btn btn-primary" id="FBPostButton">Опубликовать</button>
					</div>
				</div>
			</div>
        </div>
		
		<div class="container">
			<div class="glass-panel">

				<img src="images/greetingsImage.jpg" class="ad">
				
				<h1>Войти в&nbsp;Интернет с&nbsp;помощью:</h1>
					
				<div class="login-options">
					
					<?php
					$enabledLoginOptions = $database->getLoginOptionsIgnoringDisabledOnes();
					$out = '';
				
					foreach ($enabledLoginOptions as $value) {
						
						if ($value == 'vk') {
							ob_start();
					?>
			         <a href="#" id="VKLoginButton">
				        <span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #5596c9;"></i>
							<i class="fa fa-vk fa-stack-1x fa-inverse"></i>
						</span>
			         </a>
			         <?php 	$out = $out.ob_get_clean();
			         	} else if ($value == 'facebook') {
				         	ob_start();
			         ?>
					<a href="#" id="FBLoginButton" data-toggle="modal" data-target="#ModalFacebook">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #3b6199;"></i>
							<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php 	$out = $out.ob_get_clean();
			         	} else if ($value == 'odnoklassniki') {
				         	ob_start();
			         ?>
					<a href="#" id="odnoklassiniLoginButton" data-toggle="modal" data-target="#modalOdnoklassniki">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #f2720d;"></i>
							<i class="fa fa-odnoklassniki fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php 	$out = $out.ob_get_clean();
			         	} else if ($value == 'mobile') {
				         	ob_start();
			         ?>
					<a href="#" id="loginInputPasswordFormButton" data-toggle="modal" data-target="#modalPassword">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: rgba(102, 102, 102, 1);"></i>
							<i class="fa fa-mobile fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php 	$out = $out.ob_get_clean();
						}
					}
					echo $out;
					?>
				
				</div>
				
			</div>
		</div>
		<?php include 'includes/base/footer.php'; ?>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="//vk.com/js/api/openapi.js"></script>
		<script src="//connect.facebook.net/en_US/sdk.js"></script>
		<?php include "includes/js/loginScript.php"; ?>

	</body>
</html>