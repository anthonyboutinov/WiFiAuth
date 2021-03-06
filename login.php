<?php
	include 'includes/core/session.php';
	
	$companyName = $database->getValueByShortName('COMPANY_NAME')['VALUE'];
	
	$post = $database->getValuesForParentByShortName('POST');
	
	// Заголовок поста
	$postTitle = $post['POST_TITLE']['VALUE'];
	
	// Содержание текста для постов
	$postContent = $post['POST_TEXT']['VALUE'];
	
	//Ссылки на страницы клиентов
	$linkVK = $post['POST_LINK_VK']['VALUE'];
	$linkFB = $post['POST_LINK_FB']['VALUE'];
	
?><!DOCTYPE html><html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Добро пожаловать! — <?=$companyName;?></title>
		<script type="text/javascript" src="https://vk.com/js/api/share.js?90" charset="windows-1251"></script>
	</head>
	<body class="admin-page-login login-page"><div class="background-cover"></div>
		
		<div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-black">
				<!-- <form> -->
					<div class="modal-content narrow-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label  for="phone">Введите&nbsp;номер&nbsp;телефона,<br>чтобы&nbsp;получить<abbr title="Присылаемое сообщение бесплатно" data-toggle="tooltip" data-placement="bottom">*</abbr> код&nbsp;для&nbsp;входа:</label>
									<div class="input-group">
									  <span class="input-group-addon" id="sizing-addon2">+7</span>
									  <input type="phone" class="form-control" id="phone-form" aria-describedby="sizing-addon2">
									   <div class="input-group-btn">
										   <button class="btn btn-black" id="phoneButton"><i class="fa fa-arrow-circle-right"></i></button>
									  </div>
									</div>
							</div>
							<div class="form-group hidden" id="phone-pass-group">
								<label for="password">Введите&nbsp;4&#8209;значный&nbsp;код, доставленный&nbsp;по&nbsp;СМС:</label>
								<input type="text" class="form-control position-relative" id="password" maxlength="4">
							</div>
						</div>
						<div class="modal-footer">
							<!-- Оставить modal-footer. Он просто пустой. -->
						</div>
					</div>
				<!-- </form> -->
			</div>
		</div>

		<div class="modal fade" id="modalInternalPassword" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-black">
				<!-- <form> -->
					<div class="modal-content narrow-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label  for="pass">Спросите пароль от&nbsp;Wi-Fi у&nbsp;официанта:</label>
									<div class="input-group">
									  <input type="text" class="form-control" id="pass-form" aria-describedby="sizing-addon2">
									   <div class="input-group-btn">
										   <button class="btn btn-black" id="passButton"><i class="fa fa-arrow-circle-right"></i></button>
									  </div>
									</div>
							</div>
						</div>
						<div class="modal-footer">
							<!-- Оставить modal-footer. Он просто пустой. -->
						</div>
					</div>
				<!-- </form> -->
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
						<?php $img = $database->getValueByShortName('POST_IMG');
							if (isset($img['BLOB_VALUE'])) { ?>
							<img src="data:image/jpeg;base64,<?=base64_encode($img['BLOB_VALUE']);?>" class="modal-image">
						<?php } ?>
						<h2 style="font-size: 25px;"><?php echo $postTitle; ?></h2>
						<textarea  class="form-control" rows=5 id="FBTextArea"><?php echo $postContent; ?></textarea>
					</div>
					<div class="modal-footer">
						 <button type="button" class="btn btn-default" data-dismiss="modal" id="FBPostCancelButton">Отменить</button>
						 <button href="#" type="button" class="btn btn-primary" id="FBPostButton">Опубликовать</button>
					</div>
				</div>
			</div>
        </div>
		
		<div class="container">
			<div class="glass-panel">

				<?php $img = $database->getValueByShortName('CAPTIVE_PORTAL_IMG');
					if (isset($img['BLOB_VALUE'])) { ?>
					<img src="data:image/jpeg;base64,<?=base64_encode($img['BLOB_VALUE']);?>" class="ad">
				<?php } else echo "<h1>$companyName</h1>"; ?>
				
				<h1>Войти в&nbsp;Интернет с&nbsp;помощью:<!-- Получите&nbsp;бесплатный доступ&nbsp;в&nbsp;Интернет,<br>авторизовавшись удобным&nbsp;вам&nbsp;способом: --></h1>
					
				<div class="login-options">
					
					<?php
					$enabledLoginOptions = $database->getLoginOptionsIgnoringDisabledOnes();
				
					foreach ($enabledLoginOptions as $value) {
						
						if ($value == 'vk') {
					?>
			         <a href="#" id="VKLoginButton" >
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #5596c9;"></i>
							<i class="fa fa-vk fa-stack-1x fa-inverse"></i>
						</span>
			         </a>
			         <?php 
			         	} else if ($value == 'facebook') {
			         ?>
					<a href="#" id="FBLoginButton" data-toggle="modal" data-target="#ModalFacebook">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #3b6199;"></i>
							<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php
			         	} else if ($value == 'odnoklassniki') {
			         ?>
					<a href="#" id="odnoklassiniLoginButton" data-toggle="modal" data-target="#modalOdnoklassniki">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: #f2720d;"></i>
							<i class="fa fa-odnoklassniki fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php
			         	} else if ($value == 'mobile') {
			         ?>
					<a href="#" id="loginInputPasswordFormButton" data-toggle="modal" data-target="#modalPassword">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: rgba(102, 102, 102, 1);"></i>
<!-- 							<i class="fa fa-mobile fa-stack-1x fa-inverse"></i> -->
							<span class="sms-login-option">SMS</span>
						</span>
					</a>
					<?php
			         	} else if ($value == 'PASSWORD') {
			         ?>
					<a href="#" id="loginInputInternalPasswordFormButton" data-toggle="modal" data-target="#modalInternalPassword">
						<span class="fa-stack">
							<i class="fa fa-circle fa-stack-2x" style="color: rgba(82, 82, 82, 1);"></i>
							<span class="sms-login-option"><i class="fa fa-key"></i><span class="sr-only">Вход по паролю</span>
						</span>
					</a>
					<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php include 'includes/base/footer.php'; ?>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/jquery.numeric.min.js"></script>
		<script src="//vk.com/js/api/openapi.js"></script>
		<script src="//connect.facebook.net/en_US/sdk.js"></script>
		<?php include "includes/js/loginScript.php"; ?>
		</body>
</html>
