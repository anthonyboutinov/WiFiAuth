<?php 
 	include 'includes/core/db_config.php';

 	$email = 'optimisist@gmail.com';
 	$name = 'Spot';
 	$macAdress = '6C-AC-4C-68-38-BA';
 	$login = 'tst';
 	$password = 'tiyOHUC4YuuF65ivw6Q3';
 	$routerPassword = 'R%g~/WaYBD~WMPZy';

 	$database->addDBUser($name,$email,$macAdress,$routerPassword,$login,$password); 
?>
