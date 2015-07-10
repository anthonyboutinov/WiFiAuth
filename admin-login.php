<?php	
	include 'includes/core/session.php';
	
	if (isset($_POST['form-name'])) {
		
		// Экран восстановления пароля
		if ($_POST['form-name'] == 'forget-password-recovery-screen') {
			include 'includes/modules/admin-login-forgetPasswordRecovery.php';
			exit();
		}
		
		// Обработка формы восстановления пароля
		else if ($_POST['form-name'] == 'forget-password-recovery') {
			
			if (!isset($_POST['login'])) {
				die('DEBUG Error: no form data!');
			}
			$responce = $database->initiatePasswordReset($_POST['login']);
			$password_reset_link = $BASE_URL.'admin-login.php?l='.$responce['LOGIN'].'&t='.$responce['PASSWORD_RESET_TOKEN'];
			
			require_once('includes/core/mail_config.php');
			
			$mail->addAddress($responce['EMAIL']); 
			$mail->Subject = "Сброс пароля — Re[Spot]";
			$mail->Body    = "Для смены пароля перейдите по ссылке:\n$password_reset_link\n\nСообщение сгенерировано автоматически.";
			
			if(!$mail->send()) {
			    Notification::add('Невозможно отправить сообщение.<br>Mailer Error: ' . $mail->ErrorInfo, 'danger');
			} else {
			    Notification:add('Сообщение отправлено', 'success');
			}
			
			exit();
			
		}
		
		// Обработка формы задания нового пароля
		else if ($_POST['form-name'] == 'password-reset-set-new') {
			
			if (isset($_POST['LOGIN']) && isset($_POST['PASSWORD_RESET_TOKEN'])) {
				
				if ($_POST['password'] != $_POST['repeat-password']) {
					die("Ошибка: Пароли не совпадают!");
				}
				
				$responce = $database->setNewPasswordUsingResetPasswordToken($_POST['LOGIN'], $_POST['PASSWORD_RESET_TOKEN'], $_POST['password']);
				
				if ($responce) {
					Notification::add('Пароль успешно изменен! Теперь вы можете войти в Личный кабинет.', 'success');
				} else {
					die('Ошибка: Переданы недействительные параметры.');
				}
				
			} else {
				die('Ошибка: Переданы недействительные параметры.');
			}
			
		}
		
	}
	// Если загружается страница задания нового пароля
	else if (isset($_GET['l']) && isset($_GET['t'])) {
		
		$responce = $database->checkPasswordResetToken($_GET['l'], $_GET['t']);
		
		if ($responce) {
			include 'includes/modules/admin-login-resetPassword.php';
			exit();
		} else {
			die('Ошибка: Переданы недействительные параметры.');
		}
		
	}
	
	// Страница по умолчанию
	include 'includes/modules/admin-login-default.php';
?>