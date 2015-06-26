<?php
	
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
		
		/*
public static function addPOST() {
			
			$kind = 'info';
			
			$msg = "<b>POST request data:</b><table class=\"table\">";
		    foreach ($_POST as $key => $value) {
		        $msg = $msg."<tr><td>$key</td><td>$value</td></tr>";
		    }
		    $msg = "</table>";
		    
		    Notification::$message[$kind] =
				array_key_exists($kind, Notification::$message) ? Notification::$message[$kind].'<br>'.$msg : $msg;
		}
*/
		
	}
	
	
	
?>