<?php
	
	include 'Notification.php';

	/// Интерфейс работы с любой базой данных
	/**
	 *	@author Anthony Boutinov
	 */
	class DBInterface {
		
		protected $conn; /// mysqli подключение
		
		protected $num_queries_performed = 0; // Счетчик совершенных SQL запросов к базе данных
		
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
		
		///	Обернуть данные в кавычки для использования в SQL запросах
		/**
		 *	@author Anthony Boutinov
		 *
		 *	@param string|number|null|array &$sql	Передаваемое значение
		 *	@param bool $do_return					Возвращать ли новое значение (true) или изменять саму переменную (false). Поведение по умолчанию: изменять саму переменную.
		 *	@retval typeof($sql)					Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
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
		
		///	Универсальная функция санитизации
		/**
		 *	Если выставлена опция "оборачивать значения", то оборачивает
		 *	значение в кавычки, если это строка (и она не "NULL").
		 *	Если передается null, возаращается строка NULL. Если число, то оно
		 *	не оборачивается кавычками. Если это массив из вышеперечисленного,
		 *	делает это для каждого значения массива.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param string|number|null|array &$sql 	Значение для санитизации
		 *	@param bool $do_return					Возвращать ли новое значение (true) или изменять саму переменную (false). Поведение по умолчанию: изменять саму переменную.
 		 *	@param int $max_length					Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@retval typeof($sql)					Возаращаемое значение. По умолчанию, функция ничего не возвращает.
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
		
		///	Версия санитизации данных без оборота в кавычки (обычная санитизация)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string|number|null|array &$sql		Значение для санитизации
		 *	@param int $max_length						Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@retval typeof($sql)						Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
		protected function sanitize(&$sql, $max_length = null) {
			$this->innerSanitize($sql, false, false, $max_length);
		}
		
		///	Санитизация данных с оборотом в кавычки (продвинутая, новая санитизация)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string|number|null|array &$sql		Значение для санитизации
		 *	@param int $max_length						(Опционально) Ограничение на длину значения. По умолчанию, 2048 символов.
		 *	@param bool $do_return						(Опционально) Возвращать ли новое значение (true) или изменять саму переменную (false). Поведение по умолчанию: изменять саму переменную.
		 *	@retval typeof($sql)						Возаращаемое значение. По умолчанию, функция ничего не возвращает.
		 */
		protected function newSanitize(&$sql, $max_length = null, $do_return = false) {
			return $this->innerSanitize($sql, true, $do_return, $max_length);
		}

		/// Получить первую строку результата запроса (с уведомлением об ошибке, если возникает)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $sql							SQL запрос строкой
		 *	@param string|null $variableIdentifier		(Опционально) Название переменной, которую отображать в случае пустого ответа. По умолчанию, null
		 *	@param bool $allowNullValue					(Опционально) Позволять возвращать пустую выборку. По умолчанию, false, т.е. будет выдаваться ошибка при пустой выборке
		 *	@retval array								Массив из первой строки результата запроса
		 */
		protected function getQueryFirstRowResultWithErrorNoticing($sql, $variableIdentifier = null, $allowNullValue = false) {
			$this->num_queries_performed++;
			$result = $this->conn->query($sql);
			if (!$result) {
				Notification::add('<b>Ошибка в SQL запросе: '.$this->conn->error.'</b>'.$sql, 'danger');
				return null;
			}
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					return $row;
				}
			} else {
				if (!$allowNullValue) {
					if ($variableIdentifier == null) {
						Notification::add("<b>SQL запрос вернул 0 результатов</b>: $sql", 'danger');
					} else {
						Notification::add("Переменная <b>$variableIdentifier</b> не существует", 'danger');
					}
				}
				return null;
			}
		}
		
		/// Получить результат запроса (с уведомлением об ошибке, если возникает)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $sql		SQL запрос строкой
		 *	@retval mysqli_result
		 */
		protected function getQueryResultWithErrorNoticing($sql) {
			$this->num_queries_performed++;
			$result = $this->conn->query($sql);
			if (!$result) {
				Notification::add('<b>Ошибка в SQL запросе: '.$this->conn->error.'</b>'.$sql, 'danger');
			}
			return $result;
		}
		
		/// Преобразовать mysqli_result в ассоциативный массив, где ключи будут заданы по заданной колонке
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param mysqli_result $query_result		Резальтат запроса
		 *	@param string $key_name					Название колонки, которую сделать ключевой
		 *	@retval array
		 */
		protected function keyRowsByColumn($query_result, $key_name) {
			$out = array();
			if ($query_result->num_rows > 0) {
				while($row = $query_result->fetch_assoc()) {
					$out[$row[$key_name]] = $row;
				}
			}
			return $out;
		}
		
		///	Перевести результат запроса в массив
		/**
		 *	@author Anthony Boutinov
		 *	@param mysqli_result $result		Резальтат запроса
		 *	@retval array
		 */
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
		
		///	Получить количество совершенных SQL запросов к базе данных
		/**
		 *	@author Anthony Boutinov
		 *	@retval int
		 */
		public function getNumQueriesPerformed() {
			return $this->num_queries_performed;
		}
		
	}

?>