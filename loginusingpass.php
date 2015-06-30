<?php 
	
	include 'includes/core/session.php';
	$password = $_GET['p'];
    $passwordLogin =$database->getValueByShortName('PASSWORD')['VALUE'];

    if($passwordLogin == $password){

    	header("Location:$routerAdmin");
    } else {

    	Notification::addNextPage('Неверный пароль!','error');
    	CommonFunctions::redirect ("login.php");
    }

?>