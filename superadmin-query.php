<?php 
 	include 'includes/core/session.php';

 	if (isset($_POST['form-name'])) {

 		if ($_POST['form-name'] == 'add-user') {

 			 	$email = $_POST['email'];
			 	$name = $_POST['company-name'];
			 	$routerLogin = $_POST['router-login'];
			 	$login = $_POST['login'];
			 	$password = $_POST['password'];
			 	$routerPassword = $_POST['router-token'];
			   
			 	$database->addDBUser($name,$email,$routerLogin,$routerPassword,$login,$password);
				Notification::addNextPage('Пользователь добавлен!','success');
				CommonFunctions::redirect('superadmin-clients.php');

 		} else if ($_POST['form-name'] == 'enable-disable-user') {
                   
 				$active=$_POST['active'];
 				$id_db_user=$_POST['idUser'];
 				$database->setActiveDBUser($active,$id_db_user);
 		} else if($_POST['form-name'] == 'superadmin-confirm'){

	 			$password = $_POST['password'];
	 			$result = $database->superadminConfirmPassword($password);

	 			echo $result;
 		} else if ($_POST['form-name'] == 'fix-var-table') {
			$database->fixVarTable();
		}

 	}
?>
