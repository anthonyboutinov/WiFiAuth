<?php
include 'includes/core/db_config.php';
  $firstName =$_POST['fname'];
  $lastName = $_POST['lname'];
  $ref = $_POST['ref'];
  $logOpt =$_POST['logOpt'];
  $bDate= $_POST['bdate'];
  
  $database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 
	?> 