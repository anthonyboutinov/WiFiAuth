<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="includes/bootstrap-3.3.4-dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="includes/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">

<!-- Animate.css -->
<link rel="stylesheet" href="includes/css/animate.min.css">

<!-- Main style -->
<link rel="stylesheet" href="includes/css/style.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->

<script>// По визуальной оценке, на чистом JavaScript этот скрипт работает в два раза быстрее
	window.onload = function(){
		
		// Hacks for Safari
		
		// если не Safari
		if (!(navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0))  {
		   var selects = document.getElementsByTagName("select");
		   for (var i = 0; i < selects.length; i++) {
			   selects[i].className = selects[i].className + ' all-but-safari';
		   }
		} else {
			var _body = document.getElementsByTagName("body.admin-page-login")[0];
			_body.className = _body.className + ' safari-only';
		}
	};
</script>