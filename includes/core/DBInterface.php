<?php
	
	include 'includes/core/Notification.php';

	class DBInterface {
		
		protected $conn;
		
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
		
		protected function sanitize($sql) {
			if (is_array($sql)) {
				foreach ($sql as $key => $value) {
					$sql[$key] = $this->conn->real_escape_string($value);
				}
				return $sql;
			} else {
				return $this->conn->real_escape_string($sql);
			}
		}
		
		protected function getHash($password) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		protected function getQueryFirstRowResultWithErrorNoticing($sql, $variableIdentifier = null, $allowNullValue = false) {
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
			while($row = $result->fetch_assoc()) {
				$out[$i++] = $row;
			}
			return $out;
		}
		
		/*
public function getMoreOf($function, $from, $to) {
			return call_user_func($function, $from, $to);
		}
*/
		
	}

?>