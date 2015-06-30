<?php 
 	include 'includes/core/session.php';

 	$email = $_POST['email'];
 	$name = $_POST['company-name'];
 	$routerLogin = $_POST['router-login'];
 	$login = $_POST['login'];
 	$password = $_POST['password'];
 	$routerPassword = $_POST['router-token'];
   
 	$database->addDBUser($name,$email,$routerLogin,$routerPassword,$login,$password);
	Notification::addNextPage('Пользователь добавлен!','success');
	CommonFunctions::redirect('superadmin-clients.php');


?>
