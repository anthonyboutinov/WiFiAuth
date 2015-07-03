<?php
	
	include_once 'CommonFunctions.php';
	
	/**
	 *	class Notification
	 *
	 *	Статический класс Уведомление, в который можно добавлять сообщения (add)
	 *	и получать список всех сообщений (getMessages).
	 *	Сообщения группируются по их типу. Тип сообщения можно указать 
	 *	при добавлении сообщения. Тип сообщения может принимать любое значение 
	 *	из множества (primary, success, info, warning, danger, error).
	 *	По умолчанию, сообщения имеют тип info.
	 */
	class Notification {

		private static $message = array();

		const SESSION_VAR_PREFIX = 'Notification-';


		/**
		 *	add
		 *
		 *	Добавить уведомление
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($msg) (string)	Сообщение
		 *	@param ($kind) (string)	Тип сообщения. По умолчанию, 'warning'.
		 */	
		public static function add($msg, $kind = 'info') {
			
			if ($kind == 'error') {
				$kind = 'danger';
			}
			
			if (is_array($msg)) {
				$msg = print_r($msg, true);
			} else {
				
				// убрать повторяющиеся пробелы
				$msg = preg_replace('/\s+/', ' ',$msg);
			
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
		
		
		/**
		 *	getMessages
		 *
		 *	Получить массив сообщений
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@return (array[kind => message (string)])		Сообщения
		 */
		public static function getMessages() {
			return Notification::$message;
		}
		
		
		/**
		 *	addNextPage
		 *
		 *	Добавляет уведомление на следующую страницу, которая будет загружена
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($msg) (string)							Сообщение
		 *	@param ($kind) (string)							Тип сообщения. По умолчанию, 'warning'.
		 */
		public static function addNextPage($msg, $kind = 'warning') {
			$_SESSION['Notification-'.$kind] = (isset($_SESSION['Notification-'.$kind]) ? $_SESSION['Notification-warning'].'<br>' : '').$msg;
		}
		
	}
	
	// добавить POST Notification данные
	foreach ($_SESSION as $key => $value) {
		if (CommonFunctions::startsWith(Notification::SESSION_VAR_PREFIX, $key)) {
			Notification::add($value, substr($key, strlen(Notification::SESSION_VAR_PREFIX)));
			unset($_SESSION[$key]);
		}
	}
	
	
	
?>