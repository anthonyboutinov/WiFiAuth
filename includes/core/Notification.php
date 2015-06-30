<?php
	
	include_once 'CommonFunctions.php';
	
	/**
	 *	class Notification
	 *
	 *	����������� ����� �����������, � ������� ����� ��������� ��������� (add)
	 *	� �������� ������ ���� ��������� (getMessages).
	 *	��������� ������������ �� �� ����. ��� ��������� ����� ������� 
	 *	��� ���������� ���������. ��� ��������� ����� ��������� ����� �������� 
	 *	�� ��������� (primary, success, info, warning, dander).
	 *	�� ���������, ��������� ����� ��� info.
	 */
	class Notification {
		
		private static $message = array();
		
		const SESSION_VAR_PREFIX = 'Notification-';
				
		public static function add($msg, $kind = 'info') {
			
			if (is_array($msg)) {
				$msg = print_r($msg, true);
			} else {
			
				// ��������� SQL ��� � ���� <pre></pre>, ���� �������� ���
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
		
		public static function getMessages() {
			return Notification::$message;
		}
		
		public static function addSessionMessage($msg, $kind = 'warning') {
			$_SESSION['Notification-'.$kind] = (isset($_SESSION['Notification-'.$kind]) ? $_SESSION['Notification-warning'].'<br>' : '').$msg;
		}
		
	}
	
	// �������� POST Notification ������
	foreach ($_SESSION as $key => $value) {
		if (CommonFunctions::startsWith(Notification::SESSION_VAR_PREFIX, $key)) {
			Notification::add($value, substr($key, strlen(Notification::SESSION_VAR_PREFIX)));
			unset($_SESSION[$key]);
		}
	}
	
	
	
?>