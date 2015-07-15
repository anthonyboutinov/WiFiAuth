<?php
	
	include_once 'CommonFunctions.php';
	
	///	Статический класс Уведомление, в который можно добавлять сообщения и получать их список
	/**
	 *	Статический класс Уведомление, в который можно добавлять сообщения (add)
	 *	и получать список всех сообщений (getMessages).
	 *	Сообщения группируются по их типу. Тип сообщения можно указать 
	 *	при добавлении сообщения. Тип сообщения может принимать любое значение 
	 *	из множества (`primary`, `success`, `info`, `warning`, `danger`, `error`).
	 */
	class Notification {

		private static $message = array(); /// Хранилище сообщений

		const SESSION_VAR_PREFIX = 'Notification-'; /// Префикс для ключей в `$_SESSION`


		///	Добавить уведомление
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $msg							Сообщение
		 *	@param string $kind							(Опционально) Тип сообщения. По умолчанию, `'info'`
 		 *	@param bool $remove_repeating_whitespaces	(Опционально) Убрать ли из сообщения повторяющиеся символы пробела. По умолчанию, `true`
		 */	
		public static function add($msg, $kind = null, $remove_trailing_whitespaces = true) {
			
			if (!isset($kind)) {
				$kind = 'info';
			}
			
			else if ($kind == 'error') {
				$kind = 'danger';
			}
			
			if (is_array($msg)) {
				$msg = print_r($msg, true);
			} else {
				
				if ($remove_trailing_whitespaces == true) {
					// убрать повторяющиеся пробелы
					$msg = preg_replace('/\s+/', ' ',$msg);
				}
			
				// обрамляет SQL код в теги <pre></pre>, если замечает его
				$lookupForSQL = ['select', 'insert into'];
				$foundSQL = false;
				foreach ($lookupForSQL as $value) {
					if (preg_match('/'.$value.'/i', $msg, $matches)) {
						$foundSQL = true;
						break;
					}
				}
				if ($foundSQL) {
					$msg = preg_replace('/'.$matches[0].'/', '<pre>'.$matches[0], $msg, $count = 1);
					$msg = $msg.'</pre>';
				}
				
			}
			
			Notification::$message[$kind] =
				array_key_exists($kind, Notification::$message) ? Notification::$message[$kind].'<br>'.$msg : $msg;
		}
		
		
		///	Получить массив сообщений
		/**
		 *	@author Anthony Boutinov
		 *	@retval array		Массив в виде `array[kind => message (string)]`
		 */
		public static function getMessages() {
			return Notification::$message;
		}
		
		
		///	Вывести уведомление на следующей странице, которая будет загружена
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $msg		Сообщение
		 *	@param string $kind		(Опционально) Тип сообщения. По умолчанию, `'warning'`
		 */
		public static function addNextPage($msg, $kind = 'warning') {
			$_SESSION['Notification-'.$kind] = (isset($_SESSION['Notification-'.$kind]) ? $_SESSION['Notification-warning'].'<br>' : '').$msg;
		}
		
	}
	
	// Добавляет $_SESSION Notification данные
	if (isset($_SESSION)) {
		foreach ($_SESSION as $key => $value) {
			if (CommonFunctions::startsWith(Notification::SESSION_VAR_PREFIX, $key)) {
				Notification::add($value, substr($key, strlen(Notification::SESSION_VAR_PREFIX)));
				unset($_SESSION[$key]);
			}
		}
	}
	
	
	
?>