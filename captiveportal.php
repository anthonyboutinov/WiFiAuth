<?php
	$_SESSION['router-login'] 		= $_GET['l'];
	$_SESSION['router-password']	= $_GET['p'];

	$BASE_URL_NO_SLASH = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
	header("Location: $BASE_URL_NO_SLASH/login.php");
	exit();	
?>