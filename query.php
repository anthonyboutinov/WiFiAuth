<?php
	$current_page_is_not_protected = true;
	include 'includes/core/session.php';

  $firstName =$_POST['fname'];
  $lastName = $_POST['lname'];
  $ref = $_POST['ref'];
  $logOpt =$_POST['logOpt'];
  $bDate= $_POST['bdate'];
  
  $database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 
?>