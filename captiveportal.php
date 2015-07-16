<?php
	session_start();
	
	$_SESSION['router-login'] 		= $_GET['l'];
	$_SESSION['router-password']	= password_hash($_GET['p'], PASSWORD_BCRYPT);

// 	echo $_SESSION['router-login'].'<br>'.$_SESSION['router-password'];
	$BASE_URL_NO_SLASH = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
	header("Location: $BASE_URL_NO_SLASH/login.php");
	exit();
?>