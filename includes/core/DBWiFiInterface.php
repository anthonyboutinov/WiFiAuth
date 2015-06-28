<?php
	
	include 'includes/core/DBInterface.php';

	class DBWiFiInterface extends DBInterface {
		
		var $id_db_user;
		
		var $tablePageLimit;
		var $dashboardTablePreviewLimit;
		
		function __construct($servername, $username, $password, $dbname, $macAddress, $routerPassword) {
			parent::__construct($servername, $username, $password, $dbname);
			
			// Check web user credentials
			$this->id_db_user = $this->getWebUserByAuthenticatingViaMACAddress($macAddress, $routerPassword);			
			
			// Get other data
			$this->tablePageLimit = getNumberValue($this->getValueByShortName('TABLE_PAGE_LIMIT'));
			$this->dashboardTablePreviewLimit = getNumberValue($this->getValueByShortName('DASHBOARD_TABLE_PREVIEW_LIMIT'));
						
		}
		
		private function getWebUserByAuthenticatingViaMACAddress($macAddress, $routerPassword) {
			$macAddress = $this->sanitize($macAddress);
			$routerPassword = $this->sanitize($routerPassword);
			
			$sql = 'SELECT ID_DB_USER, IS_ACTIVE, ROUTER_PASSWORD FROM CM$DB_USER WHERE'." MAC_ADDRESS='$macAddress'";
			
			$result = $this->conn->query($sql);
			if ($result === false) {
				die("Error with query $sql");
			}
			
			if ($result->num_rows == 1) {
				while($row = $result->fetch_assoc()) {
					if (password_verify($row['ROUTER_PASSWORD'], $routerPassword)) { // 			
						if ($row["IS_ACTIVE"] == 'F') {
							die("Error #1: Router with MAC address $macAddress is disabled.");
						} else {
							return $row['ID_DB_USER'];
						}
					} else {
						die("Error #2: Credentials for router with MAC address $macAddress are incorrect. 0");
						// Íà ñàìîì äåëå, ïàðîëü íå âåðåí (â öåëÿõ áåçîïàñíîñòè, êîíå÷íîìó ïîëüçîâàòåëþ íèêîãäà íå ñîîáùàåòñÿ, ÷òî ïðîáëåìû ñ ÷åì-òî êîíêðåòíûì. Âñåãäà íåîáõîäèìî ïèñàòü, ÷òî íåâåðåíà âñÿ ïàðà ëîãèí/ïàðîëü.)
					}
				}
			} else {
				die("Error #2: Credentials for router with MAC address $macAddress are incorrect. 1");
				// Íà ñàìîì äåëå, ëîãèí íå íàéäåí (â öåëÿõ áåçîïàñíîñòè,... ñì. âûøå)
			}
		}


		
		private function logInDBUser($web_user, $web_password) {
			$web_user = $this->sanitize($web_user);
			$web_password = $this->sanitize($web_password);
			
			$sql = 'SELECT ID_DB_USER, IS_ACTIVE, PASSWORD FROM CM$DB_USER WHERE'." LOGIN='$web_user'";
			
			$result = $this->conn->query($sql);
			if ($result === false) {
				die("Error with query $sql");
			}
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					if (password_verify($web_password, $row['PASSWORD'])) {
						if ($row["IS_ACTIVE"] == 'F') {
							die("Error #3: Account $web_user is disabled.");
						} else {
							return $row['ID_DB_USER'];
						}
					} else {
						die("Error #4: Credentials for $web_user are incorrect.");
				// Íà ñàìîì äåëå, ïàðîëü íå âåðåí (â öåëÿõ áåçîïàñíîñòè, êîíå÷íîìó ïîëüçîâàòåëþ íèêîãäà íå ñîîáùàåòñÿ, ÷òî ïðîáëåìû ñ ÷åì-òî êîíêðåòíûì. Âñåãäà íåîáõîäèìî ïèñàòü, ÷òî íåâåðåíà âñÿ ïàðà ëîãèí/ïàðîëü.)
					}
				}
			} else {
				die("Error #4: Credentials for $web_user are incorrect.");
				// Íà ñàìîì äåëå, ëîãèí íå íàéäåí (â öåëÿõ áåçîïàñíîñòè,... ñì. âûøå)
			}
			
		}
		
		
		public function getValueByShortName($short_name) {
			$short_name = $this->sanitize($short_name);
			$sql = 'SELECT V.VALUE, CONVERT(V.VALUE, SIGNED) AS NUMBER_VALUE, V.BLOB_VALUE, V.ID_VAR FROM SP$VAR V WHERE V.ID_DICTIONARY IN (SELECT D.ID_DICTIONARY FROM CM$DICTIONARY D WHERE SHORT_NAME="'.$short_name.'") AND V.ID_DB_USER="'.$this->id_db_user.'"';
			return $this->getQueryFirstRowResultWithErrorNoticing($sql, $short_name);
		}
		
		public function getValueByID($id) {
			$id = $this->sanitize($id);
			$sql = 'SELECT V.VALUE, CONVERT(V.VALUE, SIGNED) AS NUMBER_VALUE, V.BLOB_VALUE, V.ID_VAR FROM SP$VAR V WHERE V.ID_DICTIONARY='.$id.' AND V.ID_DB_USER = "'.$this->id_db_user.'"';
			return $this->getQueryFirstRowResultWithErrorNoticing($sql, $id);
		}
		
		protected function appendToSQLIsOrInArrayOfValues($values, &$sql) {
			if (is_array($values)) {
				$sql = $sql.' in (';
				$isFirst = true;
				foreach ($values as $value) {
					if ($isFirst) {
						$isFirst = false;	
					} else {
						$sql = $sql.', ';
					}
					$sql = $sql.'\''.$value.'\'';
				}
				$sql = $sql.')';
			} else {
				$sql = $sql.'="'.$values.'"';
			}
		}
		
		
		/**
		 *	getValuesForParentByShortName
		 *	
		 *	Получить значения по заданному родителю (или нескольким родителям)
		 *	(словарь имеет древовидную структуру, в результате запроса 
		 *	возвращаются все записи, относящиеся к требуемой ветке словаря)
		 *
		 *	@author		Anthony Boutinov
		 *	@last_edit	25/06/15 11:00
		 *
		 *	@param ($short_names) (string или array(string))	название ветки или нескольких веток словаря, например 'VARS'
		 *	@return (array)										сложный массив, где каждая строка результата доступна по ключу SHORT_NAME
		 */
		public function getValuesForParentByShortName($short_names) {
			$short_names = $this->sanitize($short_names);
			$short_name = $short_names;
			
			$sql =
			'SELECT
				V.VALUE,
				CONVERT(V.VALUE, SIGNED) AS NUMBER_VALUE,
				V.BLOB_VALUE,
				V.ID_VAR,
				Y.SHORT_NAME,
				Y.NAME,
				Y.COMMENT,
				Y.ID_PARENT,
				W.NAME AS PARENT_NAME,
				DT.NAME AS DATA_TYPE
			FROM SP$VAR V
			LEFT JOIN CM$DICTIONARY Y ON V.ID_DICTIONARY=Y.ID_DICTIONARY
			LEFT JOIN CM$DICTIONARY W ON Y.ID_PARENT=W.ID_DICTIONARY
			LEFT JOIN CM$DICTIONARY DT ON DT.ID_DICTIONARY=Y.ID_DATA_TYPE
			WHERE
				V.ID_DICTIONARY IN (
					SELECT D.ID_DICTIONARY
					FROM CM$DICTIONARY D
					WHERE ID_PARENT IN (
						SELECT T.ID_DICTIONARY
						FROM CM$DICTIONARY T
						WHERE T.SHORT_NAME';
			$this->appendToSQLIsOrInArrayOfValues($short_name, $sql);
			$sql = $sql.'
					)
				)
				AND V.ID_DB_USER="'.$this->id_db_user.'"
				ORDER BY Y.ID_PARENT ASC';
			
			$result = $this->getQueryResultWithErrorNoticing($sql);
			return $this->keyRowsByColumn('SHORT_NAME', $result);
		}
		
		public function getDataTypesForParentByShortName($short_names) {
			$short_names = $this->sanitize($short_names);
			$short_name = $short_names;
			
			$sql = '
			select
				D.ID_DICTIONARY,
				D.SHORT_NAME,
				DT.NAME as DATA_TYPE
			from CM$DICTIONARY D
			left join CM$DICTIONARY DT on DT.ID_DICTIONARY=D.ID_DATA_TYPE
			where D.ID_PARENT IN (
				SELECT P.ID_DICTIONARY FROM CM$DICTIONARY P where P.SHORT_NAME';
			$this->appendToSQLIsOrInArrayOfValues($short_name, $sql);
			$sql = $sql.')';
						
			$result = $this->getQueryResultWithErrorNoticing($sql);
			return $this->keyRowsByColumn('SHORT_NAME', $result);
		}	
		
		private function sanitizeFromTo(&$from, &$to) {
			$from = $this->sanitize($from);
			if ($to == null) {
				$to = $this->tablePageLimit;
			} else {
				$to = $this->sanitize($to);
			}
		}
		
		public function getMainStatsTable($num_days) {

			$login_options = $this->getLoginOptions();

			foreach ($login_options as $login_option) {
				//пример:
				$name = $login_option['NAME'];
			}

		}
		
		public function getLoginActs($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql = 'select * from VW_SP$LOGIN_ACT where ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		public function getTopUsers($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql = 'select * from VW_SP$USER_LOGIN_COUNT where ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		public function getUsers($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql = 'SELECT DISTINCT LOGIN_OPTION_NAME, LINK, NAME, BIRTHDAY, ID_LOGIN_OPTION FROM VW_SP$LOGIN_ACT WHERE ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		public function getBirthdays($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);			
			$sql = 'SELECT LINK, NAME, BIRTHDAY FROM VW_SP$USER_BIRTHDAY WHERE ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		public function getShortReport() {
			$sql = 'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) = CURDATE() and A.ID_DB_USER='.$this->id_db_user; // today
			$sql = $sql.' union all '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) and A.ID_DB_USER='.$this->id_db_user; // yesterday
			$sql = $sql.' union all select Z.* from ('.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 7 DAY) and A.ID_DB_USER='.$this->id_db_user; // last week
			$sql = $sql.' union '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 30 DAY) and A.ID_DB_USER='.$this->id_db_user; // last month
			$sql = $sql.' union '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 365 DAY) and A.ID_DB_USER='.$this->id_db_user; // last year
			$sql = $sql.' union '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where A.ID_DB_USER='.$this->id_db_user.') Z'; // all time

			$result = $this->getQueryResultWithErrorNoticing($sql);
			
			$out = array();
			$names = ['сегодня', 'вчера', 'неделю', 'месяц', 'год', 'все время'];
			$i = 0;
			while($row = $result->fetch_assoc()) {
				$out[$names[$i++]] = $row['COUNT'];
			}
			return $out;
			
		}
		
		
		
		// ========= Функции, изменяющие данные в БД =========
		
		public function addUser ($first_name, $last_name, $user_href, $log_opt, $b_date)
		{
			$first_name = $this->sanitize($first_name);
			$last_name = $this->sanitize($last_name);
			$user_href =$this->sanitize($user_href);
			$log_opt = $this->sanitize($log_opt);
			$b_date =  $this->sanitize($b_date);
            if($log_opt=='vk')
            	{
            		$log_opt = 1;
            	}
            else
            {
            	$log_opt =2;
            }
            $sql  = 'select ID_USER from CM$USER where LINK="'.$user_href.'"';

            $result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $user_href, true /*не логировать, если нет результатов в запросе*/);

            if($result == null) {

            	if($log_opt==1) {																																															
            	
            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,BIRTHDAY,NAME,LINK,ID_DB_USER_MODIFIED)  values( '
            		     .$log_opt.', STR_TO_DATE("'
            			 .$b_date.'","%d.%m.%Y "),"'
                         .$first_name.' '
                         .$last_name.'","'
                         .$user_href.'", '
                         .$this->id_db_user.')';

                echo $sql;

  				} else {
            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,BIRTHDAY,NAME,LINK,ID_DB_USER_MODIFIED)  values( '
            		     .$log_opt.', STR_TO_DATE("'
            			 .$b_date.'","%m/%d/%Y "),"'
                         .$first_name.' '
                         .$last_name.'","'
                         .$user_href.'", '
                         .$this->id_db_user.')';	
  				}
            	$this->getQueryResultWithErrorNoticing($sql);

            	$sql = 'select ID_USER from CM$USER order by ID_USER desc limit 0, 1';

            	$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);

            	$id = $result['ID_USER'];
            } else {
            	$id = $result['ID_USER'];
            }


            $sql = 'insert into SP$LOGIN_ACT (ID_DB_USER,ID_USER) values ('.$this->id_db_user.', '.$id.')';
            $this->getQueryResultWithErrorNoticing($sql);

		}
		
		
		protected function postIsFine($rows, $allowEmptyStrings = false) {
			$post_is_fine = true;
			foreach ($rows as $value) {				
				if (!isset($_POST[$value])) {
					Notification::add("$POST значение для '$value' не задано!", 'danger');
					$post_is_fine = false;
				} else if ($allowEmptyStrings === false && $_POST[$value] == '') {
					$post_is_fine = false;
				}
			}
			return $post_is_fine;
		}
		

		public function processPostRequestUpdateVars($short_names) {
			
			function processFileToSQL($key, &$sql) {
				$user_file_key = $key.'_file';
					
				$fileName = $_FILES[$user_file_key]['name'];
				$tmpName  = $_FILES[$user_file_key]['tmp_name'];
				$fileSize = $_FILES[$user_file_key]['size'];
				$fileType = $_FILES[$user_file_key]['type'];
				
				$fp      = fopen($tmpName, 'r');
				$content = fread($fp, filesize($tmpName));
				$content = addslashes($content);
				fclose($fp);
				
				if(!get_magic_quotes_gpc()) {
				    $fileName = addslashes($fileName);
				}
				
				$sql = $sql.'BLOB_VALUE="'.$content.'"';
			}
			
			$rows = $this->getDataTypesForParentByShortName($short_names);
			
			$post_is_fine = true;
			foreach ($rows as $key => $value) {
								
				if ($value['DATA_TYPE'] == 'file' || $value['DATA_TYPE'] == 'text&file') {
					if ($_FILES[$key.'_file']['size'] == 0) {
						$rows[$key]['field_doesnt_need_an_update'] = true;
					}
				} else if (!isset($_POST[$key])) {
					Notification::add("POST value for '$key' is not set.", 'danger');
					$post_is_fine = false;
				}
				
			}
			
			if (!$post_is_fine) {
				return false;
			}
			
			foreach ($rows as $key => $value) {
				
				if (isset($value['field_doesnt_need_an_update']) && $value['field_doesnt_need_an_update']) {
					continue;
				}
				
				$sql = 'update SP$VAR set ';
				if ($value['DATA_TYPE'] == 'file') {
					processFileToSQL($key, $sql);
				} else {
					if ($value['DATA_TYPE'] == 'text&file') {
						processFileToSQL($key, $sql);
						$sql = $sql.', ';
					}
					$sql = $sql.'VALUE="'.htmlspecialchars($_POST[$key]).'"';
				}
				
				$sql = $sql.' WHERE ID_DB_USER='.$this->id_db_user.' AND ID_DICTIONARY='.$value['ID_DICTIONARY'];
								
				$this->getQueryResultWithErrorNoticing($sql);
			}
			
			Notification::add("Изменения сохранены!", 'success');
			return true;
			
		}
		
		public function updateDBUserPassowrd() {

			if (!$this->postIsFine(['old-password', 'password', 'repeat-password'])) {
				Notification::add('Не все поля были заполнены!', 'danger');
				return false;
			}
			
			$old_password = 	$_POST['old-password'];
			$password =			$_POST['password'];
			$repeat_password = 	$_POST['repeat-password'];
			
			if ($password != $repeat_password) {
				Notification::add('Пароли не совпадают!', 'danger');
				return false;
			}
			
			// get login and password
			$sql = 'select U.LOGIN, U.PASSWORD from CM$DB_USER U where U.ID_DB_USER='.$this->id_db_user;
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
			
			// check old password
			if (!password_verify($old_password, $result['PASSWORD'])) {
				Notification::add('Был введен неверный пароль!', 'danger');
				return false;
			}
			
			$sql = 'update CM$DB_USER set PASSWORD="'.password_hash($password, PASSWORD_BCRYPT).'" where ID_DB_USER='.$this->id_db_user;
			$this->getQueryResultWithErrorNoticing($sql);
			
			Notification::add("Пароль обновлен!", 'success');
			return true;
			
		}
		
		// ========= EOF Функции, изменяющие данные в БД =========
		
	}

	function getNumberValue($row) {
		return $row['NUMBER_VALUE'];
	}
	
	function getTextValue($row) {
		return $row['VALUE'];
	}
	
	function getBlobValue($row) {
		return $row['BLOB_VALUE'];
	}

?>