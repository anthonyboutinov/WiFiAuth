<?php 
 	include 'includes/core/db_config.php';

 	$email = $_POST['email'];
 	$name = $_POST['company'];
 	$routerLogin = $_POST['routerlogin'];
 	$login = $_POST['login'];
 	$password = $_POST['password'];
 	$routerPassword = $_POST['router'];

 	$database->addDBUser($name,$email,$routerLogin,$routerPassword,$login,$password); 
?>
