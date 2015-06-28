<?php
// 	$superadmin_page_access_level = 'MANAGER';
	/**
	 *	Задавать переменную $current_page_is_not_protected = true для страниц,
	 *	для посещения которых не требуется авторизация (кроме страницы
	 *	WifiCaptivePortal и страницы формы авторизации).
	 */
		
	session_start();
	
	require 'db_config.php';
	require 'DBWiFiInterface.php';
	
	$mac_address = null;
	$router_pasword = null;
	$cli_login = null;
	$cli_password = null;
	$id_cli = null;
	$remember_me = false;
	
	
	$wifiCaptivePage = 'login.php';
	$adminLoginPage = 'admin-login.php';
	

	// Если находится на открытой странице
	if (isset($current_page_is_not_protected) && $current_page_is_not_protected) {
		// Ничего не делать
		Notification::add('находится на открытой странице');
		unset($current_page_is_not_protected);
	} else
	// Если находится на странице авторизации
	if (basename($_SERVER['PHP_SELF']) == $adminLoginPage) { 
		Notification::add('находится на странице авторизации');
		
		// Если принимаются данные формы
		if (isset($_POST['form-name'])) {
			Notification::add('принимаются данные формы');
			if ($_POST['form-name'] == 'login' && isset($_POST['login']) && isset($_POST['password'])) {
				// Если выполняется вход
				Notification::add('выполняется вход');
				$cli_login = $_POST['login'];
				$cli_password = $_POST['password'];
				
				if (isset($_POST['remember-me'])) {
					Notification::add('Пользователь будет запомнен');
					$remember_me = true;
				} else {
					Notification::add('Пользователь не будет запомнен');
					unset($_COOKIE['remember-me']);
				    setcookie('remember-me', '', time() - 3600, '/');
				}
				
			} else if ($_POST['form-name'] == 'forgot-password') {
				Notification::add('выполняетя 1 шаг восстановления пароля (отправка заявки)');
				
				// Если выполняетя 1 шаг восстановления пароля (отправка заявки)
				// ...
			}
		}
	
	} else
	// Если находится не на странице login
	if (basename($_SERVER['PHP_SELF']) != $wifiCaptivePage) {
		Notification::add('находится не на странице login');
			
		// Если не авторизован
		if(!isset($_SESSION['id_cli'])) {
			Notification::add('не авторизован');
			
			// Запомнить, на какой странице пользователь хотел получить доступ
			$return_to_page_after_authentication = "{$_SERVER['REQUEST_URI']}";
			// Перевести на страницу авторизации
			$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/";
			header("Location: $base_url?r=$return_to_page_after_authentication"); /* Redirect browser */
			exit();
			
		} else {
			// Если авторизован, взять ID из сессии
			$id_cli = $_SESSION['id_cli'];
			Notification::add('авторизован, взять ID из сессии');
		}
		
	} else {
		// Если находится на странице login
		
		// Если авторизован
		if (isset($_SESSION['id_cli'])) {
			Notification::add('авторизован');
			// Взять ID из сессии
			$id_cli = $_SESSION['id_cli'];
			
			// Пропустить в интернет напрямую без вывода страницы login
			
			// ...
			
		} else {
			Notification::add('получаются данные от роутера для функционирования страницы login');
			
			//	Иначе получить данные от роутера для функционирования страницы login
			
			$mac_address = 'd8:a2:5e:8c:db:fe';		// MAC адрес роутера	
			$router_pasword = password_hash('password', PASSWORD_BCRYPT); // Зашифрованный пароль от роутера
		
		}
		
	}
	
	// Если есть данные для входа или страница не защищена (в последнем просто подключается к бд) 
	if (($mac_address && $router_pasword) || ($cli_login && $cli_password) || $id_cli || $current_page_is_not_protected) {
		Notification::add('есть данные для входа');
		$database = new DBWiFiInterface($servername, $username, $password, $dbname, $mac_address, $router_pasword, $cli_login, $cli_password, $id_cli);
		
		// запомнить пользователя, если валиден
		if ($database->is_valid() && $remember_me) {
			Notification::add('запоминается пользователя, если валиден');
			$year = time() + 31536000;
			setcookie('remember_me', $cli_login, $year);
		}
	} else {
		// нет данных для входа
		Notification::add('нет данных для входа');
		$database = false;
	}
	
	unset($servername);
	unset($username);
	unset($password);
	unset($dbname);
	unset($mac_address);
	unset($router_pasword);
	unset($cli_login);
	unset($cli_password);
	unset($id_cli);
	
	
?>