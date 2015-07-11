<?php
		
	require_once 'phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	
	$mail->isSMTP();                                    // Set mailer to use SMTP
	$mail->Host = 'ssl://smtp.yandex.ru';  					// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                             // Enable SMTP authentication
	$mail->Username = 'noreply@kazanwifi.ru';   		// SMTP username
	$mail->Password = 'B/buA3c=ti7vWPxsbTti[';			// SMTP password
	$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                  // TCP port to connect to
	
	$mail->From = 'noreply@kazanwifi.ru';
	$mail->FromName = 'Re[Spot]';
	
?>