<?php
	
	include 'Notification.php';

	class DBInterface {
		
		protected $conn;
		
		protected $num_queries_performed = 0;
		
		function __construct($servername, $username, $password, $dbname) {
			
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			$this->conn = $conn;
			if ($this->conn->connect_error) {
			  include 'includes/base/db_connection_failure.php';
			  exit();
			}
			
			// Change character set to utf8
			if (!$this->conn->set_charset("utf8")) {
				printf("Error loading character set utf8: %s\n", $this->conn->error);
			}
			
		}
		
		private function wrapSanitizedValue(&$sql, $do_return = false) {
			if ($sql == 'NULL' || $sql == 'null' || $sql == null) {
				if ($do_return == true) {
					return "NULL";
				} else {
					$sql = "NULL";
				}
			} else if (!is_numeric($sql)) {
				if ($do_return == true) {
					return "'$sql'";
				} else {
					$sql = "'$sql'";
				}
			}
		}
		
		/**
		 *	newSanitize
		 *
		 *	Новая функция санитизации. Кроме прочего оборачивает значение
		 *	в кавычки, если это строка (и она не "NULL"). Если передается null,
		 *	возаращается строка NULL. Если число, то оно не оборачивается
		 *	кавычками. Если это массив из вышеперечисленного, делает это
		 *	для каждого значения массива.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param (&$sql) (string | number | null | array)		Значение для санитизации
		 *	@param ($do_return) (bool)							Возвращать ли новое значение (true) или изменять саму
		 *															переменную (false). Поведение по умолчанию: изменять саму переменную.
 		 *	@param ($max_length) (int)							Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@return (typeof($sql))								Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
		private function innerSanitize(&$sql, $wrap_values, $do_return, $max_length = null) {
			
			if (!isset($wrap_values)) {
				$wrap_values = true;
			}
			if (!isset($do_return)) {
				$do_return = false;
			}
			if (!(isset($max_length))) {
				$max_length = 2048;
			}
			
			if (is_array($sql)) {
				if ($do_return == true) {
					die('DEBUG: $do_return == true не поддерживается для массивов!');
				}
				foreach ($sql as $key => $value) {
					$val = $this->conn->real_escape_string($value);
					if (strlen($val) > $max_length) {
						Notification::add(
							'<b>Warning</b>: trying to sanitize data which is '.
							length($val).' characters long! Data truncated', 'warning'
						);
						$val = substr($val, 0, $max_length);
					}
					$sql[$key] = $this->wrapSanitizedValue($val, true);
				}
			} else {
				if (strlen($sql) > $max_length) {
					Notification::add(
						'<b>Warning</b>: trying to sanitize data which is '.
						length($sql).' characters long! Data truncated', 'warning'
					);
					$sql = substr($sql, 0, $max_length);
				}
				$sql = $this->conn->real_escape_string($sql);
				
				if ($wrap_values == true) {
					return $this->wrapSanitizedValue($sql, $do_return);
				} else {
					return $sql;
				}
			}
		}
		
		/**
		 *	sanitize
		 *
		 *	Старая версия санитизации данных.
		 *	Используйте новую версию.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param (&$sql) (string | number | null | array)		Значение для санитизации
		 *	@param ($max_length) (int)							Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@return (typeof($sql))								Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
		protected function sanitize(&$sql, $max_length = null) {
			$this->innerSanitize($sql, false, false, $max_length);
		}
		
		/**
		 *	newSanitize
		 *
		 *	Санитизация данных.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param (&$sql) (string | number | null | array)		Значение для санитизации
		 *	@param ($max_length) (int)							Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@param ($do_return) (bool)							Возвращать ли новое значение (true) или изменять саму
		 *															переменную (false). Поведение по умолчанию: изменять саму переменную.
		 *	@return (typeof($sql))								Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
		protected function newSanitize(&$sql, $max_length = null, $do_return = false) {
			return $this->innerSanitize($sql, true, $do_return, $max_length);
		}
		
		protected function getHash($password) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		protected function getQueryFirstRowResultWithErrorNoticing($sql, $variableIdentifier = null, $allowNullValue = false) {
			$this->num_queries_performed++;
// 			Notification::add($sql);
			$result = $this->conn->query($sql);
			if (!$result) {
				Notification::add('<b>SQL error in query: </b>'.$sql, 'danger');
				return null;
			}
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					return $row;
				}
			} else {
				if (!$allowNullValue) {
					if ($variableIdentifier == null) {
						Notification::add("<b>Zero results for SQL query</b>: $sql", 'danger');
					} else {
						Notification::add("Variable <b>$variableIdentifier</b> does not exist", 'danger');
					}
				}
				return null;
			}
		}
		
		protected function getQueryResultWithErrorNoticing($sql) {
			$this->num_queries_performed++;
// 			Notification::add($sql);
			$result = $this->conn->query($sql);
			if (!$result) {
				Notification::add('<b>SQL error in query: </b>'.$sql, 'danger');
			}
			return $result;
		}
		
		protected function keyRowsByColumn($key_name, $query_result) {
			$out = array();
			if ($query_result->num_rows > 0) {
				while($row = $query_result->fetch_assoc()) {
					$out[$row['SHORT_NAME']] = $row;
				}
			}
			return $out;
		}
		
		public function toArray($result) {
			$out = array();
			$i = 0;
			if ($result && $result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$out[$i++] = $row;
				}
			}
			return $out;
		}
		
	}

?>