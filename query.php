<?php
include 'includes/core/db_config.php';

  $firstName ='Polub' ;//$_POST['fname'];
  $lastName ='Mikhail';
  $ref = 'http://vk.com/chopchopkazan' ;   //$_POST['ref'];
  $logOpt = 'vk';
  $bDate='1.7.1993';
  
  $database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 



// $firstName ='Polub' ;//$_POST['fname'];
// $lastName ='Mikhail'; //$_POST['lname'];
// $ref = 'http://vk.com/chopchopkazan' ;   //$_POST['ref'];
// $logOpt = 'vk';//$_POST['logOpt'];
// echo '7';
// $query = "select 'ID_USER' from 'CM$USER' where 'LINK'='$ref'";
// echo'9';
// $ath = mysql_query($query)or die(mysql_error());
// $row = mysql_fetch_assoc($ath);
// $id = $row['ID_USER'];
// if($ath){
// 	echo '14';
// 	$query = "update 'CM$USER' set 'ID_LOGIN_OPTION' = '$logOpt', 'NAME' = '$firstName'+' '+'$lastName', 'LINK' = '$ref' where 'ID_USER' = '$id' ";
// }
// else {
// 	echo '18';
// 	$query = "insert into 'CM$USER' set 'ID_LOGIN_OPTION' = '$logOpt', 'NAME' = '$firstName'+' '+'$lastName', 'LINK' = '$ref'";
// }
// $ath = mysql_query($query) or die(mysql_error()); 	
	?>