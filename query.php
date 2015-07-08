<?php
	include 'includes/core/session.php';

	if(isset($_POST['ref'])) {
	
		$firstName =$_POST['fname'];
		$lastName = $_POST['lname'];
		$ref = $_POST['ref'];
		$logOpt =$_POST['logOpt'];
		$bDate= $_POST['bdate'];
		
		$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 
	  
	} else if (isset($_POST['phone'] && $_POST['logOpt'])) {
	
		$phone =$_POST['phone'];
		$logOpt =$_POST['logOpt'];
	
		$database->addMobileUser($phone,$logOpt); 
	}

?>