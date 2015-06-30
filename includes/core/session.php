<?php
	/**
	 *	Задавать переменную $current_page_is_not_protected = true для страниц,
	 *	для посещения которых не требуется авторизация (кроме страницы
	 *	WifiCaptivePortal и страницы формы авторизации).
	 */
		
	session_start();
	
	require 'db_config.php';
	require 'DBWiFiInterface.php';
	
	$router_login = null;
	$router_pasword = null;
	$cli_login = null;
	$cli_password = null;
	$id_cli = null;
	$remember_me = false;
	
	
	$wifiCaptivePage 		= ['login.php', 'wifihotspot.php', 'query.php'];
	$adminLoginPage 		= 'admin-login.php';
	$adminMainPage 			= 'admin-dashboard.php';
	$superadminMainPage 	= 'superadmin-clients.php';
	

	// Если находится на открытой странице
	if (isset($current_page_is_not_protected) && $current_page_is_not_protected) {
		// Ничего не делать
// 		Notification::add('DEBUG (includes/core/session.php): находится на открытой странице', 'warning');
	} else
	
	// Если находится на странице авторизации
	if (basename($_SERVER['PHP_SELF']) == $adminLoginPage) { 
		
		// Если принимаются данные формы
		if (isset($_POST['form-name'])) {
			if ($_POST['form-name'] == 'login' && isset($_POST['login']) && isset($_POST['password'])) {
				// Если выполняется вход
				$cli_login = $_POST['login'];
				$cli_password = $_POST['password'];
				
				if (isset($_POST['remember-me'])) {
					$remember_me = true;
				} else {
					unset($_COOKIE['remember-me']);
				    setcookie('remember-me', '', time() - 3600, '/');
				}
				
			} else if ($_POST['form-name'] == 'forgot-password') {
				Notification::add('DEBUG (includes/core/session.php): НЕ РЕАЛИЗОВАНО! Выполняетя 1 шаг восстановления пароля (отправка заявки)', 'warning');
				
				// Если выполняетя 1 шаг восстановления пароля (отправка заявки)
				// ...
			}
		} else {
			
			// Иначе, если даные формы не принимаются, то
			if (isset($_SESSION['id_cli'])) {
				// Если авторизован, взять ID из сессии
				$id_cli = $_SESSION['id_cli'];
			}
		}
	
	} else
	
	// Если находится не на странице Login страницах
	if (!in_array(basename($_SERVER['PHP_SELF']), $wifiCaptivePage)) {
			
		// Если не авторизован
		if(!isset($_SESSION['id_cli'])) {
			
			// Запомнить, на какой странице пользователь хотел получить доступ
			$return_to_page_after_authentication = "{$_SERVER['REQUEST_URI']}";
			// Перевести на страницу авторизации
			$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
			header("Location: $base_url/$adminLoginPage?r=$return_to_page_after_authentication");
			exit();
			
		} else {
			// Если авторизован, взять ID из сессии
			$id_cli = $_SESSION['id_cli'];
		}
		
	} else {
		
		// Если находится на странице login
		
		// Если авторизован
		if (isset($_SESSION['id_cli'])) {
			// Взять ID из сессии
			$id_cli = $_SESSION['id_cli'];
			
			// Пропустить в интернет напрямую без вывода страницы login
			Notification::add('DEBUG (includes/core/session.php): НЕ РЕАЛИЗОВАНО! Пропустить в интернет напрямую без вывода страницы login', 'warning');
			
			// ...
			
		} else {
// 			Notification::add('DEBUG (includes/core/session.php): Получаются данные от роутера для функционирования страницы login', 'warning');
			
			//	Иначе получить данные от роутера для функционирования страницы login
			
			$router_login = 'chopchop';		// MAC адрес роутера	
			$router_pasword = password_hash('password', PASSWORD_BCRYPT); // Зашифрованный пароль от роутера
		
		}
		
	}
	
	// Если есть данные для входа или страница не защищена (в последнем просто подключается к бд) 
	if (($router_login && $router_pasword) || ($cli_login && $cli_password) || $id_cli || (isset($current_page_is_not_protected) && $current_page_is_not_protected)) {
		$database = new DBWiFiInterface($servername, $username, $password, $dbname, $router_login, $router_pasword, $cli_login, $cli_password, $id_cli);
		
		// Если пользователь валиден
		if ($database->is_valid()) {
			
			$_SESSION['id_cli'] = $database->getBDUserID();
			
			// Если надо запомнить, то сделать это
			if ($remember_me) {
				$year = time() + 31536000;
				setcookie('remember_me', $cli_login, $year);
			}
			
			// Если надо редиректнуть в другое место, то сделать это
			if (isset($_GET['r']) && $_GET['r'] != '') {
				$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
				header("Location: $base_url".$_GET['r']); /* Redirect browser */
				exit();
			}
			
			// Если вошел на странице логина и никуда не надо редиректить, то перейти на главную страницу
			if (basename($_SERVER['PHP_SELF']) == $adminLoginPage) {
				$to = $database->is_superadmin() ? $superadminMainPage : $adminMainPage;
				$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
				header("Location: $base_url/$to"); /* Redirect browser */
				exit();
			}
			
		}
		
		
	} else {
		// нет данных для входа
		$database = false;
	}
	
	unset($servername);
	unset($username);
	unset($password);
	unset($dbname);
	unset($router_login);
	unset($router_pasword);
	unset($cli_login);
	unset($cli_password);
	unset($id_cli);
	unset($remember_me);
	
	require 'Protector.php';
	$protector = new Protector($database);
	
?>