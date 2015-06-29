<?php 
 	include 'includes/core/db_config.php';

 	$email = 'optimisist@gmail.com';
 	$name = 'TopSpot';
 	$macAdress = '5D-AC-4C-68-38-BA';
 	$login = 'test';
 	$password = 'tiyOHUC4YuuF65ivw6Q3';
 	$routerPassword = 'R%g~/WaYBD~WMPZy';

 	$database->addDBUser($name,$email,$macAdress,$routerPassword,$login,$password); 
?>
