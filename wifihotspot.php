<?php	include 'includes/core/session.php'; ?><!DOCTYPE html>
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
	  <div class="container">
			<div class="glass-panel">
				
				<img src="images/greetingsImage.jpg" class="ad">
					
			
				<div class="lead h2"><a href="#" id="internetLogin">Войти в интернет</a></div>
			</div>
		</div>
		<?php include 'includes/base/footer.php'; ?>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/jquery.numeric.min.js"></script>
		<script src="//vk.com/js/api/openapi.js"></script>
		<?php	include 'includes/js/loginScript.php'; ?>
	</body>
</html>