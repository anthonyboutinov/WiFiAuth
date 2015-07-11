<?php
	include 'includes/core/session.php';

	if(isset($_POST['form-name'])) {
	 if($_POST['form-name'] =='addUser') {
	
		$firstName =$_POST['fname'];
		$lastName = $_POST['lname'];
		$ref = $_POST['ref'];
		$logOpt =$_POST['logOpt'];
		$bDate= $_POST['bdate'];
		
		$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 
	  
	} else if ($_POST['form-name'] =='addMobileUser') {
	
		$phone =$_POST['phone'];
		$logOpt =$_POST['logOpt'];
	
		$database->addMobileUser($phone,$logOpt); 
	}
}

?>