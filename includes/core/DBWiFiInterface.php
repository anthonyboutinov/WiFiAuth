<?php
	
	include 'DBInterface.php';

	/// Интерфейс работы с базой данных ReSpot WiFi
	/**
	 *	@author Anthony Boutinov, Михаил Полюбай
	 */
	class DBWiFiInterface extends DBInterface {
		
		var $is_router; /// Работает ли с базой данных роутер
		var $id_db_user = null; /// ID_DB_USER клиента
		var $id_db_user_editor = null; /// ID_DB_USER администратора
		var $id_min_access_level = null; /// Уровень доступа, который соответствует администратору
		
		var $superadmin_name = null; /// Имя администратора
		
		var $tablePageLimit = null; /// Лимит данных на одну страницу таблицы
		var $dashboardTablePreviewLimit = null; /// Лимит данных на одну страницу таблицы в Dashboard
		
		var $access_level_accepted = null; /// Массив с названиями уровней доступа, которыми обладает администоатор (Например, если он PRIV_MANAGER, то набор данных в массиве будет таким: ['MANAGER', 'PRIV_MANAGER'])
		
		function    __construct($servername, $username, $password, $dbname, $router_login, $router_pasword, $cli_login, $cli_password, $id_cli) {
			parent::__construct($servername, $username, $password, $dbname);
						
			if        ($router_login && $router_pasword && !$cli_login && !$cli_password && !$id_cli) {
				// Get web user credentials (from router)
				$this->id_db_user = $this->getWebUserByAuthenticatingViaRouterData($router_login, $router_pasword);
				
			} else if (!$router_login && !$router_pasword && $cli_login && $cli_password && !$id_cli) {
				// Get web user credentials (from live user) (login act)
				$this->setWebUser($cli_login, $cli_password);
				
			} else if (!$router_login && !$router_pasword && !$cli_login && !$cli_password && $id_cli) {
				// Set id
				$this->setWebUserByID($id_cli);
			
			} else if (!$router_login && !$router_pasword && !$cli_login && !$cli_password && !$id_cli) {
				// Ничего не делать, база данных подключена.
				
			} else {
				Error::fatalError('DEBUG Error: DBWiFiTinterface constructor received bad parameters');
			}
						
			if($this->is_valid()) {
				$this->pretendToBe();
			}
			
// 			Notification::add("Database interface constructor performed ".$this->num_queries_performed.' queries');
						
		}
		
		
		
# ========================================================================= #
// !PUBLIC ОПРЕДЕЛЕНИЕ ТИПА ПОЛЬЗОВАТЕЛЯ И ПРАВ ДОСТУПА
# ========================================================================= #

		/// Ведется ли работа с базой данных от лица роутера		@retval bool
		public function is_router() {
			return $this->is_router;
		}
		
		/// Ведется ли работа с базой данных от лица администратора		@retval bool
		public function is_superadmin() {
			return isset($this->id_db_user_editor);
		}
		
		/// Ведется ли работа с базой данных от клиента		@retval bool
		public function is_db_user() {
			return isset($this->id_db_user);
		}
		
		/// Валиден ли пользователь		@retval bool
		public function is_valid() {
			return (isset($this->id_db_user_editor) || isset($this->id_db_user));
		}
		
		///	Проверяет, обладает ли соответсвтуюей привелегией администратор, от лица которого ведется работа требуемому уровню
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $accl_short_name			Короткое название уровня доступа
		 *	@retval bool
		 */
		public function meetsAccessLevel($accl_short_name) {
			foreach ($this->access_level_accepted as $value) {
				if ($accl_short_name == $value) {
					return true;
				}
			}
			return false;
		}
		
		///	Проверяет, соответствует ли пароль паролю администора или нет
		/**
		 *	@author Михаил Полюбай
		 *	
		 *	@param string $password			Пароль
		 *	@retval string					Возвращает 'true' или 'false' в зависимости от того, соответствует ли пароль паролю администора или нет
		 */
		public function superadminConfirmPassword($password) {
			$this->sanitize($password);
			$sql='SELECT PASSWORD FROM CM$DB_USER WHERE ID_DB_USER='.$this->id_db_user_editor;
			$result = $this->getQueryResultWithErrorNoticing($sql);
			if ($result->num_rows == 1) {
				while($row = $result->fetch_assoc()) {
					if( password_verify($password,$row['PASSWORD'])){
						echo 'true';
					} else {
						echo 'false';
					}
				}
			}
		}
		
# ==== КОНЕЦ PUBLIC ОПРЕДЕЛЕНИЕ ТИПА ПОЛЬЗОВАТЕЛЯ И ПРАВ ДОСТУПА ==== #
# ========================================================================= #		

# =============================================================== #
// !PUBLIC ПОЛУЧЕНИЕ ID ПОЛЬЗОВАТЕЛЯ
# =============================================================== #

		///	Возвращает либо ID_DB_USER администратора, либо ID_DB_USER клиента (в таком приоритете)
		/**
		 *	@author Anthony Boutinov
		 *	@retval int
		 */
		public function getIDBDUser() {
			return isset($this->id_db_user_editor) ? $this->id_db_user_editor : $this->id_db_user;
		}
		
		/// Возвращает имя адмниистратора		@retval string
		public function getSuperadminName() {
			return $this->superadmin_name;
		}

# ==== КОНЕЦ PUBLIC ПОЛУЧЕНИЕ ID ПОЛЬЗОВАТЕЛЯ
# =============================================================== #

# ======================================================================== #
// !ПЕРВИЧНАЯ ОБРАБОТКА ПОЛЬЗОВАТЕЛЯ (АВТОРИЗАЦИЯ)
# ======================================================================== #
		
		///	Наполняет массив $access_level_accepted набором коротких названий всех привелегий, которыми обдалает администратор
		/**
		 *	@author Anthony Boutinov
		 */
		private function setAcceccLevelAcceptedArray() {
			
			// Достать значения из куки, если есть
			if (isset($_COOKIE['acceccLevelAcceptedArray'])) {
				$this->access_level_accepted = unserialize($_COOKIE['acceccLevelAcceptedArray']);
			} else {
			
				$sql = 'SELECT AL.ID_ACCESS_LEVEL, AL.SHORT_NAME FROM CM$ACCESS_LEVEL AL ORDER BY AL.ORDER ASC';
				$result = $this->toArray($this->getQueryResultWithErrorNoticing($sql));
				$result = CommonFunctions::extractSingleValueFromMultiValueArray($result, 'SHORT_NAME', 'ID_ACCESS_LEVEL');
				
				$out = array();
				foreach ($result as $key => $value) {
					if ($key >= $this->id_min_access_level) {
						$out[] = $value;
					}
				}
				
				$this->access_level_accepted = $out;
				
				// Запомнить значения на 10 минут, чтобы не спрашивать БД при каждой загрузке страницы
				setcookie("acceccLevelAcceptedArray", serialize($out), 0); // 10 mins
			}
		}
		
		///	Производит аутентификацию роутера по его логину и паролю.
		/**	
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $router_login				Логин роутера
		 *	@param string $router_password			Пароль роутера
		 *	@retval int								ID_DB_USER роутера
		 */
		private function getWebUserByAuthenticatingViaRouterData($router_login, $router_password) {
			$this->sanitize($router_login);
			$this->sanitize($router_password);
			
			$sql = 'SELECT ID_DB_USER, IS_ACTIVE, ROUTER_PASSWORD FROM CM$DB_USER WHERE IS_SUPERADMIN=\'F\' AND ROUTER_LOGIN=\''.$router_login.'\'';
			$result = $this->conn->query($sql);
			if ($result === false) {
				Error::fatalError("Error with query $sql");
			}
			
			if ($result->num_rows == 1) {
				while($row = $result->fetch_assoc()) {
					if (password_verify($row['ROUTER_PASSWORD'], $router_password)) {
						if ($row["IS_ACTIVE"] == 'F') {
							Error::fatalError("Ошибка: Обслуживание роутера $router_login приостановлено.");
						} else {
							$this->is_router = true;
							return $row['ID_DB_USER'];
						}
					} else {
						Error::fatalError("Ошибка: Параметры авторизации роутера $router_login неверны.");
					}
				}
			} else {
				Error::fatalError("Ошибка: Параметры авторизации роутера $router_login неверны.");
			}
		}

		private function processVerifiedUser($row, $web_user) {
			if ($row["IS_ACTIVE"] == 'F') {
				Notification::add("Обслуживание аккаунта $web_user приостановлено", 'danger');
				return false;
			} else {
				if ($row['IS_SUPERADMIN'] == 'T') {
					$this->id_db_user_editor = $row['ID_DB_USER'];
					$this->id_min_access_level = $row['ID_ACCESS_LEVEL'];
					$this->superadmin_name = $row['LOGIN'];
					$this->setAcceccLevelAcceptedArray();
				} else {
					$this->id_db_user = $row['ID_DB_USER'];
				}
				$this->is_router = false;
				return true;
			}
		}

		private function setWebUserByID($id) {
			$this->sanitize($id);
			
			$sql = 
			'SELECT U.ID_DB_USER, U.IS_ACTIVE, U.LOGIN, U.PASSWORD, U.ID_ACCESS_LEVEL, U.IS_SUPERADMIN
			FROM CM$DB_USER U WHERE U.ID_DB_USER='.$id;
			
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $id);
			return $this->processVerifiedUser($result, $id);
		}
		
		private function updateNumFailedAttempts($id_db_user) {
			$sql =
			'update CM$DB_USER set
				LAST_FAILED_ATTEMPT=NOW(),
				NUM_FAILED_ATTEMPTS=IFNULL(NUM_FAILED_ATTEMPTS, 0)+1,
				ID_DB_USER_MODIFIED='.$id_db_user.'
			where ID_DB_USER='.$id_db_user;
			$this->getQueryResultWithErrorNoticing($sql);
			Notification::add("Логин и(или) пароль неверны", 'danger');
		}
		
		///	Сбрасывает поля, связанные с отметками о неверных вводах пароля пользователем
		/**
		 *	@author Anthony Boutinov
		 *	@param int $id_db_user				ID_DB_USER
		 */
		private function resetFailedLoginAtteptFields($id_db_user) {
			// Сбросить счетчики неверных паролей
			$sql =
			'update CM$DB_USER set
				UNLOCK_AT=NULL,
				LAST_FAILED_ATTEMPT=NULL,
				NUM_FAILED_ATTEMPTS=NULL,
				ID_DB_USER_MODIFIED='.$id_db_user.'
			where ID_DB_USER='.$id_db_user;
			$this->getQueryResultWithErrorNoticing($sql);
		}
		
		///	Производит аутентификацию клиента (в Личный Кабинет)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $web_user				Логин
		 *	@param string $web_password			Пароль
		 *	@retval bool						Возвращает false, если авторизация не прошла, и true, если прошла успешно
		 */
		private function setWebUser($web_user, $web_password) {
			$this->sanitize($web_user);
			$this->sanitize($web_password);
			
			$maxAttempts = 6; // Максимальное количество попыток, разрешенное на непрерывные попытки ввести пароль
			$caution_wait_time_interval = 30; /* 30 минут: если неверно ввел пароль и с момента этого ввода пароля прошло больше 30 минут, то в новую попытку ввода пароля счетчик неверных попыток сбросится */
			
			$sql = 
			'SELECT U.ID_DB_USER, U.IS_ACTIVE, U.LOGIN, U.PASSWORD, U.ID_ACCESS_LEVEL, U.IS_SUPERADMIN, U.NUM_FAILED_ATTEMPTS, U.LAST_FAILED_ATTEMPT,
			CASE WHEN U.UNLOCK_AT IS NOT NULL
		       THEN \'T\' ELSE \'F\'
			END AS IS_LOCKED,
			CASE WHEN U.UNLOCK_AT IS NOT NULL AND U.UNLOCK_AT < NOW()
		       THEN \'T\' ELSE \'F\'
			END AS PERFORM_UNLOCK,
			CASE WHEN U.LAST_FAILED_ATTEMPT IS NOT NULL AND U.LAST_FAILED_ATTEMPT < DATE_SUB(NOW(), INTERVAL '.$caution_wait_time_interval.' MINUTE)
		       THEN \'T\' ELSE \'F\'
			END AS LAST_FAILED_ATTEMPT_WAS_LONG_AGO
			FROM CM$DB_USER U WHERE LOWER(U.LOGIN)=LOWER(\''.$web_user.'\')';
			
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $web_user, true);
			
			// Если логин найден
			if ($result) {
				
				// если пользователь заблокирован
				if ($result['IS_LOCKED'] == 'T') {
					
					// Если наступило время его разблокировать
					if ($result['PERFORM_UNLOCK'] == 'T') {
						
						// Разблокировать его:
						$sql =
						'update CM$DB_USER set
							UNLOCK_AT=NULL,
							LAST_FAILED_ATTEMPT=NULL,
							ID_DB_USER_MODIFIED='.$result['ID_DB_USER'].'
						where ID_DB_USER='.$result['ID_DB_USER'];
						$this->getQueryResultWithErrorNoticing($sql);
						// Продолжить после этого if'а проверку пароля...
						
					} else { // Если время разблокировки еще не наступило
						// Оповестить, что еще рано
						Notification::add("Доступ к аккаунту ".$web_user." временно заблокирован по причине многократных попыток ввода неверного пароля в течение короткого времени. Блокировка истечет через час.", 'danger');
						return false;
						
					}
				}
				
				// Если пароли совпадают
				if (password_verify($web_password, $result['PASSWORD'])) {
					
					// Сбросить счетчики неверных паролей
					$this->resetFailedLoginAtteptFields($result['ID_DB_USER']);
					
					// Обработать верифицированного пользователя: запомнить в себе (this)
					return $this->processVerifiedUser($result, $web_user);
					
				} else /* Если пароли не совпдают */ {
					
					// Если уже были неверные попытки ввода пароля
					if ($result['NUM_FAILED_ATTEMPTS'] != null) {
						
						// Если эти попытки были давно
						if ($result['LAST_FAILED_ATTEMPT_WAS_LONG_AGO'] == 'T') {
							// То сбросить счетчики неверных паролей
							$this->resetFailedLoginAtteptFields($result['ID_DB_USER']);
						}

						// Если лимит ошибок превышен
						if ($result['NUM_FAILED_ATTEMPTS'] >= $maxAttempts - 1) {

							// Заблокировать временно
							$sql =
							'update CM$DB_USER set
								LAST_FAILED_ATTEMPT=NULL,
								NUM_FAILED_ATTEMPTS=NULL,
								UNLOCK_AT=DATE_ADD(NOW(), INTERVAL 1 HOUR),'./* 1 час: установить момент разблокировки на «через 1 час» */'
								ID_DB_USER_MODIFIED='.$result['ID_DB_USER'].'
							where ID_DB_USER='.$result['ID_DB_USER'];
							$this->getQueryResultWithErrorNoticing($sql);
							Notification::add("Вы несколько раз неверно ввели пароль в течение короткого времени. Доступ в личный кабинет заблокирован на 1 час.", 'danger');
							
						} else /* Если не превышен еще*/ {

							// Увеличить счетчик и выдать предупреждение
							$this->updateNumFailedAttempts($result['ID_DB_USER']);
							
							// Если осталось 3 попытки ввода пароля
							if ($result['NUM_FAILED_ATTEMPTS'] >= $maxAttempts - 3) {
								
								// Предупредить об этом
								$num_tries_left = $maxAttempts - $result['NUM_FAILED_ATTEMPTS'] - 1;
								$postfix = $num_tries_left == 1 ? 'а' : 'и';
								Notification::add('У вас осталось '.$num_tries_left.' попытк'.$postfix.' ввода пароля, прежде чем вход в Личный кабинет будет (временно) заблокирован.', 'warning');
							}
						}
						
					} else /* Если пароль введен неверно в первый раз */ {
						// Увеличить счетчик и выдать предупреждение
						$this->updateNumFailedAttempts($result['ID_DB_USER']);
					}
					
					return false;
				}
			} else /* Если логин не найден */ {
				Notification::add("Логин и(или) пароль неверны", 'danger');
				return false;
			}
		}
		
		/// Функция «притвориться...»
		/**
		 *	Позволяет суперадмину производить
		 *	операции от лица клиента с заданным идентификатором.
		 *	Данные получаются из $_POST массива, если они есть.
		 *	Чтобы вызвать эту функцию, необходимо отправить форму pretend-to-be
		 *	на любую страницу портала.
		 *	
		 *	@author Anthony Boutinov
		 */
		private function pretendToBe() {
			if (isset($_POST['form-name']) && $_POST['form-name'] == 'pretend-to-be' && isset($_POST['pretend-to-be'])) {
				$_SESSION['pretend-to-be'] = $_POST['pretend-to-be'];
			}
						
			if (isset($_SESSION['pretend-to-be']) && $this->is_superadmin()) {
				if (CommonFunctions::startsWith('/superadmin-', "{$_SERVER['REQUEST_URI']}")) {
					unset($_SESSION['pretend-to-be']);
				} else {
					$this->id_db_user = $_SESSION['pretend-to-be'];
				}
			}
		}
		
		/// Возвращает ID_DB_USER админа или клиента (в обратном приоритете)
		/**
		 *	Возвращает ID_DB_USER. Если не включена опция «Притвориться...»
		 *	и работа ведется от лица суперадмин, то возвращается его id.
		 *	Если работа ведется от лица суперадмин и он «притворяется» клиентом,
		 *	то возвращается id заданного клиента.
		 *	Если работа ведется от лица клиента, то возвращается его id.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@retval int			ID_DB_USER
		 */
		protected function getMixedDBUserID() {
			if (!isset($_SESSION['pretend-to-be']) && $this->id_db_user_editor) {
				return $this->id_db_user_editor;
			} else {
				return $this->id_db_user;
			}
		}
		
# ==== КОНЕЦ ПЕРВИЧНАЯ ОБРАБОТКА ПОЛЬЗОВАТЕЛЯ (АВТОРИЗАЦИЯ) ==== #
# ======================================================================== #


# ============================================================= #
// !ПОЛУЧЕНИЕ ДАННЫХ ИЗ СЛОВАРЯ
# ============================================================= #
		
		/// Преобразовать mysqli_result в ассоциативный массив, где ключи будут заданы по заданной колонке
		/**
		 *	Переопределяет родительский метод: задает значение по умолчанию для $key_name как 'SHORT_NAME'
		 *
		 *	@author Anthony Boutinov
		 *	
		 *	@param mysqli_result $query_result		Резальтат запроса
		 *	@param string $key_name					(Опционально) Название колонки, которую сделать ключевой
		 *	@retval array
		 */
		protected function keyRowsByColumn($query_result, $key_name = 'SHORT_NAME') {
			return parent::keyRowsByColumn($query_result, $key_name);
		}
		
		///	Получить значение из SP$VAR по SHORT_NAME
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $short_name		SHORT_NAME
		 *	@retval array					Массив из одной строки таблицы SP$VAR
		 */
		public function getValueByShortName($short_name) {
			$this->sanitize($short_name);
			$sql = 'SELECT V.VALUE, CONVERT(V.VALUE, SIGNED) AS NUMBER_VALUE, V.BLOB_VALUE, V.ID_VAR FROM SP$VAR V WHERE V.ID_DICTIONARY IN (SELECT D.ID_DICTIONARY FROM CM$DICTIONARY D WHERE SHORT_NAME="'.$short_name.'") AND V.ID_DB_USER='.$this->getMixedDBUserID();
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $short_name);
			if ($result['VALUE'] == 'T' || $result['VALUE'] == 't') {
				$result['VALUE'] = true;
			} else if ($result['VALUE'] == 'F' || $result['VALUE'] == 'f') {
				$result['VALUE'] = false;
			}
			return $result;
		}
		
		///	Получить значение из SP$VAR по ID_DICTIONARY
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $id			ID_DICTIONARY
		 *	@retval array			Массив из одной строки таблицы SP$VAR
		 */
		public function getValueByID($id) {
			$this->sanitize($id);
			$sql = 'SELECT V.VALUE, CONVERT(V.VALUE, SIGNED) AS NUMBER_VALUE, V.BLOB_VALUE, V.ID_VAR FROM SP$VAR V WHERE V.ID_DICTIONARY='.$id.' AND V.ID_DB_USER='.$this->getMixedDBUserID();
			return $this->getQueryFirstRowResultWithErrorNoticing($sql, $id);
		}
		
		///	Получить значения по заданному родителю
		/**
		 *	Получить значения по заданному родителю (или нескольким родителям)
		 *	(словарь имеет древовидную структуру, в результате запроса 
		 *	возвращаются все записи, относящиеся к требуемой ветке словаря)
		 *
		 *	@author		Anthony Boutinov
		 *
		 *	@param string|array $short_names		Название ветки или нескольких веток словаря, например 'VARS'
		 *	@retval array							Сложный массив, где каждая строка результата доступна по ключу SHORT_NAME
		 */
		public function getValuesForParentByShortName($short_names) {
			$this->newSanitize($short_names);
			
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
			$this->appendToSQLIsOrInArrayOfValues($short_names, $sql);
			$sql = $sql.'
					)
				)
				AND V.ID_DB_USER="'.$this->id_db_user.'"
				ORDER BY W.ORDER, Y.ORDER ASC';

			$result = $this->getQueryResultWithErrorNoticing($sql);
			return $this->keyRowsByColumn($result);
		}
		
		public function getDataTypesForParentByShortName($short_names) {
			$this->sanitize($short_names);
			
			$sql =
			'select
				D.ID_DICTIONARY,
				D.SHORT_NAME,
				DT.NAME as DATA_TYPE
			from CM$DICTIONARY D
			left join CM$DICTIONARY DT on DT.ID_DICTIONARY=D.ID_DATA_TYPE
			where D.ID_PARENT IN (
				SELECT P.ID_DICTIONARY FROM CM$DICTIONARY P where P.SHORT_NAME';
			$this->appendToSQLIsOrInArrayOfValues($short_names, $sql);
			$sql = $sql.') order by D.ORDER ASC';
						
			$result = $this->getQueryResultWithErrorNoticing($sql);
			return $this->keyRowsByColumn($result);
		}
		
# ==== КОНЕЦ ПОЛУЧЕНИЕ ДАННЫХ ИЗ СЛОВАРЯ ==== #
# ============================================================= #


# ===================================================================================== #
// !PROTECTED ОБЩИЕ МЕТОДЫ ПРЕОБРАЗОВАНИЯ ДАННЫХ
# ===================================================================================== #
		
		///	Санитизировать одновременно обе переменные: from и to
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param mixed &$from
		 *	@param mixed &$to
		 */
		protected function sanitizeFromTo(&$from, &$to) {
			$this->sanitize($from);
			if ($to == null) {
				$to = $this->tablePageLimit;
			} else {
				$this->sanitize($to);
			}
		}
		
		///	Добавляет в $sql «равно ...» или «в массиве значений (...)»
		/**
		 *	Добавляет в $sql «равно ...» или «в массиве значений (...)»
		 *	для заданного значения, которое может быть либо строкой либо массивом строк
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param string|array $values			Значения(е)
		 *	@param string &$sql					SQL строка запроса, в которую дописать данные
		 */
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
					$sql = $sql.$value;
				}
				$sql = $sql.')';
			} else {
				$sql = $sql.'='.$values;
			}
		}
		
# ==== КОНЕЦ PROTECTED ОБЩИЕ МЕТОДЫ ПРЕОБРАЗОВАНИЯ ДАННЫХ ==== #
# ===================================================================================== #


# ======================================================================================= #
// !ПОЛУЧЕНИЕ ДАННЫХ О OFFSET, LIMIT ДЛЯ ВСЕХ ТАБЛИЦ
# ======================================================================================= #
		
		/// Подготовиться для запросов (таблиц) на страницах Dashboard		@author Anthony Boutinov
		public function prepareForDashboardTableQueries() {
			$this->dashboardTablePreviewLimit = $this->getValueByShortName('DASHBOARD_TABLE_PREVIEW_LIMIT')['NUMBER_VALUE'];
		}
		
		/// Подготовиться для запросов (таблиц) на страницах кроме Dashboard		@author Anthony Boutinov
		public function prepareForDefaultTableQueries() {
			$this->tablePageLimit = $this->getValueByShortName('TABLE_PAGE_LIMIT')['NUMBER_VALUE'];
		}
		
		/// Получить лимит в запросе таблиц Dashboard
		/**
		 *	@author Anthony Boutinov
		 *	@retval int			Количество строк, которые необходимо отображать
		 */
		public function getDashboardTablePreviewLimit() {
			return $this->dashboardTablePreviewLimit;
		}
		
		/// Получить лимит в запросе таблиц кроме Dashboard
		/**
		 *	@author Anthony Boutinov
		 *	@retval int			Количество строк, которые необходимо отображать
		 */
		public function getTablePageLimit() {
			return $this->tablePageLimit;
		}
		
# ==== КОНЕЦ ПОЛУЧЕНИЕ ДАННЫХ О OFFSET, LIMIT ДЛЯ ВСЕХ ТАБЛИЦ ==== #
# ======================================================================================= #


# ========================================================================== #
// !ПОЛУЧЕНИЕ ОБЫЧНЫХ ДАННЫХ ИЗ ТАБЛИЦ
# ========================================================================== #

		
		/// Получить акты входа
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $from			Лимит от
		 *	@param int|null $to			(Опционально) Лимит до. По умолчанию, $tablePageLimit
		 *	@retval mysqli_result
		 */
		public function getLoginActs($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql = 'select * from VW_SP$LOGIN_ACT
			where
				NAME<>\'password\'
				and ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		/// Получить постоянных пользователей
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $from			Лимит от
		 *	@param int|null $to			(Опционально) Лимит до. По умолчанию, $tablePageLimit
		 *	@retval mysqli_result
		 */
		public function getTopUsers($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql = 'select * from VW_SP$USER_LOGIN_COUNT
			WHERE
				ID_DB_USER='.$this->id_db_user.'
				AND NAME<>\'password\'
			LIMIT '.$from.', '.$to;
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		/// Получить недавних пользователей
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $from			Лимит от
		 *	@param int|null $to			(Опционально) Лимит до. По умолчанию, $tablePageLimit
		 *	@retval mysqli_result
		 */
		public function getUsers($from = 0, $to = null) {
			$this->sanitizeFromTo($from, $to);
			$sql =
			'SELECT DISTINCT LOGIN_OPTION_NAME, LINK, NAME, BIRTHDAY, ID_LOGIN_OPTION
			FROM VW_SP$LOGIN_ACT
			WHERE
				ID_DB_USER='.$this->id_db_user.'
				AND ID_USER<>(SELECT ID_USER WHERE NAME=\'password\')
			LIMIT '.$from.', '.$to;
			Error::fatalError("DEBUG: Произошел вызов функции GETUSERS. Удалите сообщение об ошибке в коде!");
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		public function getCountSocialNetworkUsers() {
			$sql =
			'SELECT COUNT(LINK) FROM (SELECT DISTINCT LINK  
			FROM VW_SP$LOGIN_ACT
			WHERE
				ID_DB_USER='.$this->id_db_user.'
				AND LOGIN_OPTION_SHORT_NAME<>"PASSWORD"
				AND LOGIN_OPTION_SHORT_NAME<>"mobile") as PostCount';
			return $this->getQueryFirstRowResultWithErrorNoticing($sql)['COUNT(LINK)'];
		}

		public function getFriendsSocialNetworkUsers(){

			$sql =
			'SELECT DISTINCT NUM_FRIENDS 
			FROM VW_SP$LOGIN_ACT
			WHERE
				ID_DB_USER='.$this->id_db_user.'
				AND LOGIN_OPTION_SHORT_NAME<>"PASSWORD"
				AND LOGIN_OPTION_SHORT_NAME<>"mobile"';

			$result = $this->getQueryResultWithErrorNoticing($sql);
			
			// Итерировать по ним
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {

					$friendsCount = $friendsCount + $row['NUM_FRIENDS'];

				}
			}

			return $friendsCount;	
		}
		/// Получить дни рождения
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $from			Лимит от
		 *	@param int|null $to			(Опционально) Лимит до. По умолчанию, $tablePageLimit
		 *	@param int|bool|other		(Опционально) Составить запрос в интеллектуальном представлении
		 *	@retval mysqli_result
		 */
		public function getBirthdays($from = 0, $to = null, $intellectual_view = 1) {
			$this->sanitizeFromTo($from, $to);
			$intellectual_view = $intellectual_view == 1 ? true : false;
			
			if (!$intellectual_view) {
				$sql = 'SELECT * FROM VW_SP$USER_BIRTHDAY WHERE ID_DB_USER='.$this->id_db_user.' limit '.$from.', '.$to;
			} else {
				$sql = 
				'select 
					Z.LINK,
					Z.NAME,
					Z.BIRTHDAY,
					Z.DAYS_UNTIL,
					Z.WILL_TURN,
					Z.LOGIN_OPTION_NAME,
					Z.LOGIN_OPTION_SHORT_NAME,
					Z.LOGIN_COUNT,
					Z.LOGIN_COUNT_INV + DAYS_UNTIL as COEF
				from (
					SELECT
						Y.LINK,
						Y.NAME,
						Y.BIRTHDAY,
						CASE
							WHEN DATEDIFF(Y.CURRBIRTHDAY, CURDATE()) < 0 THEN
								DATEDIFF(Y.NEXTBIRTHDAY, CURDATE())
							ELSE 
								DATEDIFF(Y.CURRBIRTHDAY, CURDATE())
						END AS DAYS_UNTIL,
						TIMESTAMPDIFF(YEAR, Y.B_DATE, CURDATE()) + 1 AS WILL_TURN,
						Y.LOGIN_OPTION_NAME,
						Y.LOGIN_OPTION_SHORT_NAME,
						CAST(1 / Y.LOGIN_COUNT * 30'./*коэффициент*/' AS UNSIGNED) as LOGIN_COUNT_INV,
						Y.LOGIN_COUNT
						
					from (
				
						SELECT DISTINCT
							W.LINK,
							W.NAME,
							W.BIRTHDAY AS B_DATE,
							DATE_FORMAT(W.BIRTHDAY, "%d.%m.%Y") AS BIRTHDAY,
							W.BIRTHDAY + INTERVAL(YEAR(CURRENT_TIMESTAMP) - YEAR(W.BIRTHDAY)) + 0 YEAR AS CURRBIRTHDAY,
							W.BIRTHDAY + INTERVAL(YEAR(CURRENT_TIMESTAMP) - YEAR(W.BIRTHDAY)) + 1 YEAR AS NEXTBIRTHDAY,
							W.LOGIN_OPTION_NAME,
							W.LOGIN_OPTION_SHORT_NAME,
							(
								select COUNT(E.ID_USER)
								from SP$LOGIN_ACT E
								where E.ID_DB_USER=W.ID_DB_USER
								and E.ID_USER=W.ID_USER
								and DATEDIFF(E.DATE_CREATED, CURDATE()) < 81'./*количество дней захода, которые учитываются*/'
							) AS LOGIN_COUNT
						FROM VW_SP$LOGIN_ACT W
						WHERE W.BIRTHDAY IS NOT NULL
						AND W.ID_DB_USER='.$this->id_db_user.'
				
					) Y
					order by DAYS_UNTIL = 0 desc, DAYS_UNTIL asc
				) Z
				where Z.DAYS_UNTIL < 32'./*максимальное количество дней до дня рождения*/'
				ORDER BY COEF = 0 ASC, COEF ASC;';
			}
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		/// Получить список опций входа кроме тех, которые отключены
		/**
		 *	@author Anthony Boutinov
		 *	@retval array	Простой массив со значениями (только список значений по колонке SHORT_NAME)
		 */
		public function getLoginOptionsIgnoringDisabledOnes() {
			$sql = 'select LO.SHORT_NAME
			from VW_CM$LOGIN_OPTION LO
			where LO.ID_LOGIN_OPTION in (select V.ID_DICTIONARY from SP$VAR V 
               where (V.VALUE <>\'F\' AND V.VALUE<>\'\')
               and ID_DB_USER ='.$this->id_db_user.')';
			$result = $this->toArray($this->getQueryResultWithErrorNoticing($sql));
			return CommonFunctions::extractSingleValueFromMultiValueArray($result, 'SHORT_NAME');
		}
		
		/// Список опций входа
		var $loginOptions = null;
		
		/// Получить список опций входа
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param bool $cross_with_limit_acts			(Опционально) Не ограничивать ли только теми, по которым были заходы пользователей. По умолчанию, false (ограничивать)
		 *	@retval array								SQL результат в виде массива
		 */
		public function getLoginOptions($cross_with_limit_acts = false) {
			if ($this->loginOptions != null) {
				return $this->loginOptions;
			}
			if ($cross_with_limit_acts) {
				$sql = 'select * from VW_CM$LOGIN_OPTION';
			} else {
				$sql =
				'SELECT LO.*
				FROM VW_CM$LOGIN_OPTION LO
				where LO.ID_LOGIN_OPTION in
				(
				    select U.ID_LOGIN_OPTION
				    from CM$USER U
				    left join SP$LOGIN_ACT LA on LA.ID_USER=U.ID_USER
				    where LA.ID_DB_USER='.$this->id_db_user.'
				    and ID_LOGIN_OPTION<>(
				    	select ID_DICTIONARY
				    	from CM$DICTIONARY
				    	where SHORT_NAME=\'PASSWORD\'
				    )
				)';
			}
			$this->loginOptions = $this->toArray($this->getQueryResultWithErrorNoticing($sql));
			return $this->loginOptions;
		}
		
		///	Получить цвета (цветовую схему)
		/**
		 *	@author Anthony Boutinov
		 *	@retval array			Простой массив с CSS цветами (значения по колонке 'COLOR')
		 */
		public function getColors() {
			$sql = 
			'select 
			D.NAME as COLOR
			from CM$DICTIONARY D where D.ID_PARENT in (
				SELECT N.ID_DICTIONARY
				from CM$DICTIONARY N
				WHERE N.SHORT_NAME=\'CHART_COLORS_DEFAULT\'
			) ORDER BY D.ORDER ASC';
			$result = $this->toArray($this->getQueryResultWithErrorNoticing($sql));
			return CommonFunctions::extractSingleValueFromMultiValueArray($result, 'COLOR');
		}
		
		/// Получить главную таблицу статистики
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $num_days		За какое поличество дней производить выборку
		 *	@retval array				SQL результат в виде массива
		 */
		public function getMainStatsTable($num_days) {

			$login_options = $this->getLoginOptions(true);

			$sql =
			'SELECT
				CONCAT(U.JSON_DATE_BEGINNING, U.JSON_DATE_MIDDLE, U.JSON_DATE_END) as JSON_DATE,';
				
			$isFirst = true;
			foreach ($login_options as $login_option) {
				$name = $login_option['NAME'];
				
				if ($isFirst === true) {
					$isFirst = false;
				} else {
					$sql = $sql.',';
				}
				
				$sql = $sql.'U.'.$login_option['SHORT_NAME'];
				
			}
	
				
			$sql = $sql.' FROM (
			SELECT
				DATE_FORMAT(D.DATE, \'new Date(%Y, \') AS JSON_DATE_BEGINNING,
	            DATE_FORMAT(D.DATE, \'%m\') - 1 AS JSON_DATE_MIDDLE,
	            DATE_FORMAT(D.DATE, \', %d)\') AS JSON_DATE_END,';			
			
			$isFirst = true;
			foreach ($login_options as $login_option) {
				$name = $login_option['NAME'];
				
				if ($isFirst === true) {
					$isFirst = false;
				} else {
					$sql = $sql.',';
				}
				
				$sql = $sql.
				'(
					SELECT COUNT(LA.ID_LOGIN_ACT) AS TOTAL
					FROM SP$LOGIN_ACT LA
					INNER JOIN CM$USER U ON U.ID_USER=LA.ID_USER
					LEFT JOIN VW_CM$LOGIN_OPTION LO ON LO.ID_LOGIN_OPTION=U.ID_LOGIN_OPTION
					WHERE LA.ID_DB_USER='.$this->id_db_user.'
						AND LO.ID_LOGIN_OPTION='.$login_option['ID_LOGIN_OPTION'].'
						AND DATE(LA.DATE_CREATED)=D.DATE
					) AS \''.$login_option['SHORT_NAME'].'\'';
				
			}

			$sql = $sql.
			'FROM (
				SELECT A.DATE 
				FROM (
					SELECT CURDATE( ) - INTERVAL( A.A + ( 10 * B.A ) + ( 100 * C.A ) ) DAY AS DATE
					FROM (
						SELECT 0 AS A
						UNION ALL SELECT 1 
						UNION ALL SELECT 2 
						UNION ALL SELECT 3 
						UNION ALL SELECT 4 
						UNION ALL SELECT 5 
						UNION ALL SELECT 6 
						UNION ALL SELECT 7 
						UNION ALL SELECT 8 
						UNION ALL SELECT 9
						) AS A
						CROSS JOIN (
			
						SELECT 0 AS A
						UNION ALL SELECT 1 
						UNION ALL SELECT 2 
						UNION ALL SELECT 3 
						UNION ALL SELECT 4 
						UNION ALL SELECT 5 
						UNION ALL SELECT 6 
						UNION ALL SELECT 7 
						UNION ALL SELECT 8 
						UNION ALL SELECT 9
						) AS B
						CROSS JOIN (
			
						SELECT 0 AS A
						UNION ALL SELECT 1 
						UNION ALL SELECT 2 
						UNION ALL SELECT 3 
						UNION ALL SELECT 4 
						UNION ALL SELECT 5 
						UNION ALL SELECT 6 
						UNION ALL SELECT 7 
						UNION ALL SELECT 8 
						UNION ALL SELECT 9
						) AS C
					) A
				WHERE A.DATE BETWEEN DATE_SUB(CURDATE(), INTERVAL '.$num_days.' DAY) AND CURDATE() 
			) D
			ORDER BY D.DATE DESC) U';
// 			Notification::add($sql);
						
			return $this->toArray($this->getQueryResultWithErrorNoticing($sql));
		}
		
		/// Количество входов по каждому способу входа (для легенды под графиком)
		var $loginCountByLoginOption = null;
		
		/// Получить количество входов по каждому способу входа (для легенды под графиком) вместе с процентным соотношением
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $num_days		За какое поличество дней производить выборку
		 *	@retval array				SQL результат в виде массива
		 */
		public function getLoginCountByLoginOption($num_days) {
			
			if ($this->loginCountByLoginOption != null) {
				return $this->loginCountByLoginOption;
			}
			
			$this->sanitize($num_days);
			
			$sql = '
			select
				count(A.ID_LOGIN_ACT) AS LOGIN_COUNT,
				U.ID_LOGIN_OPTION AS ID_LOGIN_OPTION,
				O.SHORT_NAME,
				O.NAME,
				A.ID_DB_USER AS ID_DB_USER
			from SP$LOGIN_ACT A
			inner join CM$USER U on A.ID_USER = U.ID_USER
			left join VW_CM$LOGIN_OPTION O on U.ID_LOGIN_OPTION = O.ID_LOGIN_OPTION
			where
				A.ID_DB_USER='.$this->id_db_user.'
				and O.ID_LOGIN_OPTION<>(
			    	select ID_DICTIONARY
			    	from CM$DICTIONARY
			    	where SHORT_NAME=\'PASSWORD\'
			    )
				and DATE(A.DATE_CREATED) >= DATE_SUB(CURDATE(), INTERVAL '.$num_days.' DAY)
			group by U.ID_LOGIN_OPTION, A.ID_DB_USER, O.SHORT_NAME, O.NAME
			order by U.ID_LOGIN_OPTION';
									
			$result = $this->getQueryResultWithErrorNoticing($sql);
			$out = $this->toArray($result);
			
			$total_count = 0;
			foreach ($out as $value) {
				$total_count += $value['LOGIN_COUNT'];
			}
			
			$i = 0;
			foreach ($out as $value) {
				$value['PERCENTAGE'] = (int)(($value['LOGIN_COUNT'] / $total_count * 100) * 10) / 10;
				$out[$i++] = $value;
			}
			
			$this->loginCountByLoginOption = $out;
			return $out;
		}
		
		/// Получить краткий отчет
		/**
		 *	@author Anthony Boutinov
		 *	@retval array				SQL результат в виде ассоциативного массива с ключами ['сегодня', 'вчера', 'неделю', 'месяц', 'год', 'все время']
		 */
		public function getShortReport() {
			$sql = 'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) = CURDATE() and A.ID_DB_USER='.$this->id_db_user; // today
			$sql = $sql.' union all '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) and A.ID_DB_USER='.$this->id_db_user; // yesterday
			$sql = $sql.' union all select Z.* from ('.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 7 DAY) and A.ID_DB_USER='.$this->id_db_user; // last week
			$sql = $sql.' union '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) and A.ID_DB_USER='.$this->id_db_user; // last month
			$sql = $sql.' union '.'select count(A.ID_LOGIN_ACT) as COUNT from SP$LOGIN_ACT A where DATE(A.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) and A.ID_DB_USER='.$this->id_db_user; // last year
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
		
		public function getPostImage($id_db_user, $timestamp = NULL) {
			#TODO: Временной штамп учитывать!
			// timestamp передается текстом в формате 'YYYY-MM-DD-hh-mm-ss'
			$this->newSanitize($id_db_user);
			$sql = 'select BLOB_VALUE from SP$VAR
			where ID_DB_USER='.$id_db_user.'
			and ID_DICTIONARY=(SELECT ID_DICTIONARY FROM CM$DICTIONARY WHERE SHORT_NAME="POST_IMG")';
			return $this->getQueryFirstRowResultWithErrorNoticing($sql, "POST_IMG", false)['BLOB_VALUE'];
		} 

# ==== КОНЕНЦ ПОЛУЧЕНИЕ ОБЫЧНЫХ ДАННЫХ ИЗ ТАБЛИЦ ==== #
# ========================================================================== #
		
		
# =================================================================== #
// !Функции, изменяющие данные в БД 
# =================================================================== #
		
		/// Добавить пользователя по номеру телефона
		/**
		 *	@author Михаил Полюбай
		 *	
		 *	@param string|int $phone			Номер телефона
		 *	@param string $log_opt				LOGIN_OPTION SHORT_NAME
		 */
		public function addMobileUser($phone, $log_opt)
		{
			$this->sanitize($phone);
			$this->sanitize($log_opt);
				
			$sql = 'select ID_DICTIONARY from CM$DICTIONARY where SHORT_NAME="'.$log_opt.'"';
			$log_opt = $this->getQueryFirstRowResultWithErrorNoticing($sql)['ID_DICTIONARY'];

			mysqli_autocommit($this->conn,FALSE);
			
            $sql  = 'select ID_USER from CM$USER where NAME="'.$phone.'"';
            $result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $phone, true /*не выдавать ошибку, если нет результатов в запросе*/);
            if($result == null) {

            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,LINK,NAME,ID_DB_USER_MODIFIED)  values('
            		     .$log_opt.',"'
            		     .'tel:+'.$phone.'","'
                         .$phone.'",'
                         .$this->id_db_user.')';

            	$this->getQueryResultWithErrorNoticing($sql);
            	$sql = 'select ID_USER from CM$USER order by ID_USER desc limit 0, 1';
            	$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
            }
        	$id = $result['ID_USER']; // либо из result перед if'ом, либо из result внутри него

            $sql = 'insert into SP$LOGIN_ACT (ID_DB_USER,ID_USER) values ('.$this->id_db_user.', '.$id.')';
            $this->getQueryResultWithErrorNoticing($sql);

            // Commit transaction
			mysqli_commit($this->conn);

			// Close connection
			mysqli_close($this->conn);


		}
		
		/// Добавить пользователя
		/**
		 *	@author Михаил Полюбай
		 *	
		 *	@param string $first_name			Имя
		 *	@param string $last_name			Фамилия
		 *	@param string $user_href			Ссылка на профиль
		 *	@param string $log_opt				LOGIN_OPTION SHORT_NAME
		 *	@param string $b_date				День рождения
		 */
		public function addUser($first_name, $last_name, $user_href, $log_opt, $b_date,$friendsCount)
		{
			$this->sanitize($first_name);
			$this->sanitize($last_name);
			$this->sanitize($user_href);
			$this->sanitize($log_opt);
			$this->sanitize($b_date);
			$this->sanitize($friendsCount);

			$sql = 'select ID_DICTIONARY from CM$DICTIONARY where SHORT_NAME="'.$log_opt.'"';
			$logOption = $this->getQueryFirstRowResultWithErrorNoticing($sql)['ID_DICTIONARY'];
            $sql  = 'select ID_USER from CM$USER where LINK="'.$user_href.'"';
            $result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $user_href, true /*не выдавать ошибку, если нет результатов в запросе*/);
            if($result == null) {

            	mysqli_autocommit($this->conn,FALSE);

            	if($log_opt == 'vk'){
            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,BIRTHDAY,NAME,LINK,NUM_FRIENDS,ID_DB_USER_MODIFIED)  values('
            		     .$logOption.', STR_TO_DATE("'
            			 .$b_date.'","%d.%m.%Y "),"'
                         .$first_name.' '
                         .$last_name.'","'
                         .$user_href.'", '
                         .$friendsCount.','
                         .$this->id_db_user.')';
					} else if($log_opt=='facebook') {

		            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,BIRTHDAY,NAME,LINK,NUM_FRIENDS,ID_DB_USER_MODIFIED)  values('
            		     .$logOption.', STR_TO_DATE("'
            			 .$b_date.'","%m/%d/%Y "),"'
                         .$first_name.' '
                         .$last_name.'","'
                         .$user_href.'", '
                         .$friendsCount.','
                         .$this->id_db_user.')';
					}
            	$this->getQueryResultWithErrorNoticing($sql);
            	$sql = 'select ID_USER from CM$USER where ID_DB_USER_MODIFIED ='.$this->id_db_user.' order by ID_USER desc limit 0, 1';
            	$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
            }
        	$id = $result['ID_USER']; // либо из result перед if'ом, либо из result внутри него

            $sql = 'insert into SP$LOGIN_ACT (ID_DB_USER,ID_USER) values ('.$this->id_db_user.', '.$id.')';
            $this->getQueryResultWithErrorNoticing($sql);

            // Commit transaction
			mysqli_commit($this->conn);

			// Close connection
			mysqli_close($this->conn);
		}

		function addInstagramUser($fullName,$ref,$friendsCount){

			$sql = 'select ID_DICTIONARY from CM$DICTIONARY where SHORT_NAME="instagram"';
			$logOption = $this->getQueryFirstRowResultWithErrorNoticing($sql)['ID_DICTIONARY'];

			$sql  = 'select ID_USER from CM$USER where LINK="'.$ref.'"';
            $result = $this->getQueryFirstRowResultWithErrorNoticing($sql, $ref, true /*не выдавать ошибку, если нет результатов в запросе*/);
             if($result == null) {
             	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,NAME,LINK,NUM_FRIENDS,ID_DB_USER_MODIFIED)  values('
            		     .$logOption.',"'
                         .$fullName.'","https://instagram.com/'
                         .$ref.'", '
                         .$friendsCount.','
                         .$this->id_db_user.')';
            	
            	$this->getQueryResultWithErrorNoticing($sql);

            	$sql = 'select ID_USER from CM$USER where ID_DB_USER_MODIFIED ='.$this->id_db_user.' order by ID_USER desc limit 0, 1';
            	$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
				}

        	$id = $result['ID_USER']; // либо из result перед if'ом, либо из result внутри него

            $sql = 'insert into SP$LOGIN_ACT (ID_DB_USER,ID_USER) values ('.$this->id_db_user.', '.$id.')';
            $this->getQueryResultWithErrorNoticing($sql);

		}

		function addPasswordUser() {

			$sql = 'select ID_DICTIONARY from CM$DICTIONARY where SHORT_NAME="PASSWORD"';
			$logOption = $this->getQueryFirstRowResultWithErrorNoticing($sql)['ID_DICTIONARY'];

			$sql  = 'select ID_USER from CM$USER where NAME="password" and ID_DB_USER_MODIFIED ='.$this->id_db_user;
            $result = $this->getQueryFirstRowResultWithErrorNoticing($sql, true /*не выдавать ошибку, если нет результатов в запросе*/);
            if($result == null) {
            	mysqli_autocommit($this->conn,FALSE);
            	
            	$sql = 'insert into CM$USER 
            	         (ID_LOGIN_OPTION,NAME,ID_DB_USER_MODIFIED)  values('
            		     .$logOption.',"password",'
                         .$this->id_db_user.')';

            	$this->getQueryResultWithErrorNoticing($sql);
            	$sql = 'select ID_USER from CM$USER where ID_DB_USER_MODIFIED ='.$this->id_db_user.' order by ID_USER desc limit 0, 1';
            	$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
            }

            $id = $result['ID_USER'];
            $sql = 'insert into SP$LOGIN_ACT (ID_DB_USER,ID_USER) values ('.$this->id_db_user.', '.$id.')';
            $this->getQueryResultWithErrorNoticing($sql);

            // Commit transaction
			mysqli_commit($this->conn);

			// Close connection
			mysqli_close($this->conn);
		}
		
		/// Проверяет, заданы для POST значения по заданным ключам ($rows)
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param array $rows					Ключи, по которым надо проверить массив $_POST
		 *	@param bool $allowEmptyStrings		Позволять пустые строки ('')
		 *	@retval bool
		 */
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
		
		/// Обработать POST запрос: обновить SP$VARS
		/**
		 *	Используется для сохранения настроек.
		 *
		 *	@author Anthony Boutinov
		 *	
		 *	@param array $short_names			CM$DICTIONARY.SHORT_NAME массив
		 *	@retval bool						Возвращает статус: успешно или нет
		 */
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
			
			$rows = $this->getDataTypesForParentByShortName($short_names); // sanitized inside
			
			$post_is_fine = true;
			foreach ($rows as $key => $value) {
								
				if ($value['DATA_TYPE'] == 'file' || $value['DATA_TYPE'] == 'text&file') {
					if ($_FILES[$key.'_file']['size'] == 0) {
						$rows[$key]['field_doesnt_need_an_update'] = true;
					}
				} else if ($value['DATA_TYPE'] == 'checkbox') {
					if (isset($_POST[$key])) {
						$_POST[$key] = "T";
					} else {
						$_POST[$key] = "F";
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
		
		///	Получить список несуществующих переменных для заданного ID_DB_USER
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $id_db_user			ID_DB_USER
		 *	@retval mysqli_result
		 */
		private function getNonexistentVarsForDBUser($id_db_user) {
			$sql =
			'SELECT
				E.ID_DICTIONARY,
				E.SHORT_NAME,
				E.DEFAULT_VALUE
			FROM CM$DICTIONARY E
			WHERE
				E.ID_PARENT IN
				(
					SELECT B.ID_DICTIONARY
					FROM CM$DICTIONARY B
					WHERE B.ID_PARENT IN
					(
						SELECT F.ID_DICTIONARY
						FROM CM$DICTIONARY F
						WHERE F.SHORT_NAME = \'VARS\'
					)
				)
				AND E.ID_DICTIONARY NOT IN
				(
					SELECT V.ID_DICTIONARY
					FROM SP$VAR V
					WHERE V.ID_DB_USER='.$id_db_user.'
				)';
			return $this->getQueryResultWithErrorNoticing($sql);
		}
		
		/// Получить клиентов
		/**
		 *	@author Михаил Полюбай, Anthony Boutinov
		 *	@param string $order_by 	(Опционально) По чему сортировать. Возможные значения: 'ID_DB_USER', 'TRAFFIC', 'NAME' (NAME по умолчанию).
		 *	@retval mysqli_result
		 */
		public function getClients($order_by = 'NAME') {
			
			if ($order_by == 'ID_DB_USER') {
				$order_by = 'D.ID_DB_USER ASC';
			} else if ($order_by == 'TRAFFIC') {
				$order_by = 'D.IS_ACTIVE DESC, LOGIN_ACT_COUNT_MONTH DESC';
			} else {
				$order_by = 'D.IS_ACTIVE DESC, B.VALUE ASC';
			}
			
			$sql =
			'SELECT
				C.VALUE AS EMAIL,
				B.VALUE AS COMPANY_NAME,
				D.*,
				CASE
					WHEN D.ID_DB_USER=D.ID_DB_USER_MODIFIED THEN "самим собой"
					ELSE P.LOGIN
				END AS DB_USER_MODIFIED,
				(
					SELECT COUNT(LA.ID_LOGIN_ACT)
					FROM SP$LOGIN_ACT LA
					WHERE LA.ID_DB_USER=D.ID_DB_USER
					AND DATE(LA.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
				) AS LOGIN_ACT_COUNT_MONTH
			FROM
				CM$DB_USER D, SP$VAR B, SP$VAR C, CM$DB_USER P
			WHERE
				D.IS_SUPERADMIN=\'F\' 
				AND D.ID_DB_USER = B.ID_DB_USER
	            AND D.ID_DB_USER = C.ID_DB_USER
	            AND D.ID_DB_USER_MODIFIED = P.ID_DB_USER
				AND B.ID_DICTIONARY = (
					SELECT ID_DICTIONARY 
					FROM CM$DICTIONARY
		            WHERE SHORT_NAME = "COMPANY_NAME"
	            )
				AND C.ID_DICTIONARY = (
					SELECT ID_DICTIONARY 
					FROM CM$DICTIONARY
		            WHERE SHORT_NAME = "EMAIL"
	            )
			ORDER BY '.$order_by;
			return  $this->getQueryResultWithErrorNoticing($sql); 
		}
		
		/// Получить количество администраторов и клиентов
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param bool $count_deactivated		(Опционально) Считать вмете с деактивированными. По умолчанию, не считать их.
		 *	@retval int
		 */
		public function getDBUsersCount($count_deactivated = false) {
			$sql =
			'select count(ID_DB_USER) as COUNT from CM$DB_USER where IS_SUPERADMIN=\'F\' '.
			'union select count(ID_DB_USER) as COUNT from CM$DB_USER where IS_SUPERADMIN=\'T\'';
			if ($count_deactivated == false) {
				$sql = $sql.' and IS_ACTIVE=\'T\'';
			}
			$array = $this->toArray($this->getQueryResultWithErrorNoticing($sql));
			return CommonFunctions::extractSingleValueFromMultiValueArray($array, 'COUNT');
		}
		
		/// Получить информацию о клиенте
		/**
		 *	@author Anthony Boutinov, Михаил Полюбай
		 *	
		 *	@param int|string $id_db_user		ID_DB_USER
		 *	@retval array						Одномерный массив
		 */
		public function getClient($id_db_user) {
			$this->newSanitize($id_db_user);
			
			$sql =
			'SELECT
				C.VALUE AS EMAIL,
				B.VALUE AS COMPANY_NAME,
				D.*,
				CASE
					WHEN D.ID_DB_USER=D.ID_DB_USER_MODIFIED THEN "самим собой"
					ELSE P.LOGIN
				END AS DB_USER_MODIFIED,
				(
					SELECT COUNT(LA.ID_LOGIN_ACT)
					FROM SP$LOGIN_ACT LA
					WHERE LA.ID_DB_USER=D.ID_DB_USER
					AND DATE(LA.DATE_CREATED)=CURDATE()
				) AS LOGIN_ACT_COUNT_TODAY,
				(
					SELECT COUNT(LA.ID_LOGIN_ACT)
					FROM SP$LOGIN_ACT LA
					WHERE LA.ID_DB_USER=D.ID_DB_USER
					AND DATE(LA.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
				) AS LOGIN_ACT_COUNT_MONTH,
				(
					SELECT COUNT(LA.ID_LOGIN_ACT)
					FROM SP$LOGIN_ACT LA
					WHERE LA.ID_DB_USER=D.ID_DB_USER
					AND DATE(LA.DATE_CREATED) > DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
				) AS LOGIN_ACT_COUNT_YEAR
			FROM
				CM$DB_USER D, SP$VAR B, SP$VAR C, CM$DB_USER P
			WHERE
				D.ID_DB_USER='.$id_db_user.'
				AND D.ID_DB_USER = B.ID_DB_USER
	            AND D.ID_DB_USER = C.ID_DB_USER
	            AND D.ID_DB_USER_MODIFIED = P.ID_DB_USER
				AND B.ID_DICTIONARY = (
					SELECT ID_DICTIONARY 
					FROM CM$DICTIONARY
		            WHERE SHORT_NAME = "COMPANY_NAME"
	            )
				AND C.ID_DICTIONARY = (
					SELECT ID_DICTIONARY 
					FROM CM$DICTIONARY
		            WHERE SHORT_NAME = "EMAIL"
	            )';
            return $this->getQueryFirstRowResultWithErrorNoticing($sql);
		}
		
		/// Произвести INSERT в SP$VAR
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $id_dictionary			ID_DICTIONARY
		 *	@param string|int|null $value		Значение
		 *	@param int $id_db_user				ID_DB_USER
		 *	@param bool $echo_sql				(Опционально) Выполнить echo запросов. По умолчанию, не делать этого
		 *	@retval mysqli_result
		 */
		protected function insertVarValue($id_dictionary, $value, $id_db_user, $echo_sql = false) {
			$value = isset($value) ? "'".$value."'" : 'NULL';
			$sql = 'INSERT INTO SP$VAR (ID_DICTIONARY,VALUE,ID_DB_USER) VALUES ('.$id_dictionary.','.$value.','.$id_db_user.');';
			
			if ($echo_sql == true) {
				echo $sql."<br>";
			}
			
			$this->getQueryResultWithErrorNoticing($sql);
		}
		
		/// Добавить клиента
		/**
		 *	@author Михаил Полюбай, Anthony Boutinov
		 *	
		 *	@param string $name					Название компании
		 *	@param string $email				Email
		 *	@param string $routerLogin			Логин роутера
		 *	@param string $router_password		Пароль роутера
		 *	@param string $login				Логин
		 *	@param string $password				Пароль
		 */
		public function addDBUser($name, $email, $routerLogin, $router_password, $login, $password) {
			$this->sanitize($routerLogin);
			$this->sanitize($router_password);
			$this->sanitize($login);
			$this->sanitize($password);
			$this->sanitize($name);
			$this->sanitize($email);

			$password = password_hash($password, PASSWORD_BCRYPT);

			$sql='INSERT INTO CM$DB_USER 
			(IS_SUPERADMIN, ROUTER_LOGIN, 
			ROUTER_PASSWORD, LOGIN, PASSWORD, ID_DB_USER_MODIFIED) 
			VALUES (
				"F","'.$routerLogin.'","'
				.$router_password.'","'
				.$login.'","'
				.$password.'","'
				.$this->id_db_user_editor.'")';
			$this->getQueryResultWithErrorNoticing($sql);

			$sql = 'SELECT ID_DB_USER FROM CM$DB_USER WHERE IS_SUPERADMIN=\'F\' ORDER BY ID_DB_USER DESC LIMIT 0, 1';
			$id_db_client = $this->getQueryFirstRowResultWithErrorNoticing($sql)['ID_DB_USER'];

			// Получить информацию по полям, которых нет в таблице SP$VAR для этого пользователя
			$dictionary_result = $this->getNonexistentVarsForDBUser($id_db_client);

			if ($dictionary_result->num_rows > 0) {
				while ($row = $dictionary_result->fetch_assoc()) {
					if ($row['SHORT_NAME']=='EMAIL'){
						// Вставить email
						$this->insertVarValue($row['ID_DICTIONARY'], $email, $id_db_client);
					} else if ($row['SHORT_NAME']=='COMPANY_NAME'){
						// Вставить name
						$this->insertVarValue($row['ID_DICTIONARY'], $name, $id_db_client);
					} else {
						// Вставить значение по умолчанию для остальных
						$this->insertVarValue($row['ID_DICTIONARY'], $row['DEFAULT_VALUE'], $id_db_client);
					}
				}
			}
		}
		
		/// «Починить» SP$VARS для одного заданного ID_DB_USER
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param int $id_db_user			ID_DB_USER
		 *	@param bool $echo_sql			(Опционально) Выполнить echo запросов. По умолчанию, не делать этого
		 */
		protected function fixVarsForOneDBUser($id_db_user, $echo_sql = false) {
			// Получить информацию по полям, которых нет в таблице SP$VAR для этого пользователя
			$result = $this->getNonexistentVarsForDBUser($id_db_user);
			
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					// Вставить значение по умолчанию
					$this->insertVarValue($row['ID_DICTIONARY'], $row['DEFAULT_VALUE'], $id_db_user, $echo_sql);
				}
			}
			
		}
		
		///	«Починить» таблицу SP$VAR
		/**
		 *	«Починить» таблицу SP$VAR после добавления новых записей
		 *	в CM$DICTIONARY или после неполного добавления нового DB_USER.
		 *	Добавляет значения по умолчанию для несуществующих DB_USER'ов.
		 *	
		 *	@author Anthony Boutinov
		 */
		public function fixVarTable() {
			// Получить список всех активных ID_DB_USER
			$sql = 'select ID_DB_USER from CM$DB_USER where IS_ACTIVE=\'T\'';
			$result = $this->getQueryResultWithErrorNoticing($sql);
			
			// Итерировать по ним
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					// Добавить значение по умолчанию для каждого
					$this->fixVarsForOneDBUser($row['ID_DB_USER'], true);	
				}
			}		
		}

		/// Задать ID_DB_USER'а активным или неактивным
		/**
		 *	@author Михаил Полюбай, Anthony Boutinov
		 *	
		 *	@param string|bool $active			Сделать активным или неактивным ('T', 'F', либо bool значение)
		 *	@param int $id_db_user				ID_DB_USER
		 */
		public function setActiveDBUser($active, $id_db_user) {
			$this->sanitize($active);
			$this->sanitize($id_db_user);
			
			if ($active === 'T' || $active === true) {
				$active = 'T';
			} else if ($active === 'F' || $active === false) {
				$active = 'F';
			} else {
				Error::fatalError('DEBUG Error: Передан неверный параметр в setActiveDBUser');
			}

			$sql = 'update  CM$DB_USER set IS_ACTIVE="'.$active.'", ID_DB_USER_MODIFIED='.$this->id_db_user_editor.' where ID_DB_USER='.$id_db_user;
			$this->getQueryResultWithErrorNoticing($sql);
			
			// Если активирован
			if ($active == 'T') {
				// Починить для него недостающие переменные, если с момента его деактивации были добавлены новые VARS в CM$DICITONARY
				$this->fixVarsForOneDBUser($id_db_user);
			}
		}
		/// Обновить пароль для клиента или администратора
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@retval string	Строка вербозного ответа. Если ответ имеет форму ошибки, то вначале строки приписана строка 'danger:'
		 */
		public function updateDBUserPassowrd() {

			if (!$this->postIsFine(['old-password', 'password', 'repeat-password'])) {
				return "danger:Не все поля были заполнены!";
			}
			
			$old_password = 	$_POST['old-password'];
			$password =			$_POST['password'];
			$repeat_password = 	$_POST['repeat-password'];
			
			if ($password != $repeat_password) {
				return "danger:Пароли не совпадают!";
			}
			
			// get login and password
			$sql = 'select U.LOGIN, U.PASSWORD from CM$DB_USER U where U.ID_DB_USER='.$this->getIDBDUser();
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql);
			
			// check old password
			if (!password_verify($old_password, $result['PASSWORD'])) {
				return 'danger:Был введен неверный пароль!';
			}
			
			$sql = 'update CM$DB_USER set PASSWORD="'.password_hash($password, PASSWORD_BCRYPT).'" where ID_DB_USER='.$this->getIDBDUser();
			$this->getQueryResultWithErrorNoticing($sql);
			
			return "Новый пароль установлен!";
			
		}
				
# ==== КОНЕЦ Функции, изменяющие данные в БД ==== #
# =================================================================== #



# =================================================== #
// !ВОССТАНОВЛЕНИЕ ПАРОЛЯ
# =================================================== #
		
		/// Инициировать сброс пароля: сгенерировать токен для восстановления
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $login_or_email		Логин или email
		 *	@retval array|false					Ассоциативный массив с ключами EMAIL, LOGIN, PASSWORD_RESET_TOKEN. Если пользователь с таким логином или email не найден, возвращается false
		 */
		public function initiatePasswordReset($login_or_email) {
			$is_email = filter_var($login_or_email, FILTER_VALIDATE_EMAIL);
			$this->sanitize($login_or_email);
			
			$password_restore_token = CommonFunctions::generateRandomString(64);
			$this->sanitize($password_restore_token);
			$sql =
			'update CM$DB_USER set PASSWORD_RESET_TOKEN=\''.password_hash($password_restore_token, PASSWORD_BCRYPT).
			'\' where ';
			if ($is_email) {
				$sql = $sql.'ID_DB_USER=(
					select ID_DB_USER
					from SP$VAR
					where
						VALUE=\''.$login_or_email.'\'
						and ID_DICTIONARY=(
							select ID_DICTIONARY
							from CM$DICTIONARY
							where SHORT_NAME=\'EMAIL\'
						)
				)';
			} else {
				$sql = $sql.'login=\''.$login_or_email.'\'';
			}			
			$this->getQueryResultWithErrorNoticing($sql);
			
			$login = $login_or_email;
			$email = $login_or_email;
			
			if ($is_email) {
				$email = $login_or_email;
				$sql =
				'select LOGIN
				from CM$DB_USER
				where ID_DB_USER=(
					select ID_DB_USER
					from SP$VAR
					where
						VALUE=\''.$login_or_email.'\'
						and ID_DICTIONARY=(
							select ID_DICTIONARY
							from CM$DICTIONARY
							where SHORT_NAME=\'EMAIL\'
						)
				)';
				$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, null, true);
				if (!$result) {
					return false;
				} else {
					$login = $result['LOGIN'];
				}
			} else {
				$sql =
				'select VALUE as EMAIL from SP$VAR
				where ID_DB_USER=(
						select ID_DB_USER from CM$DB_USER
						where LOGIN=\''.$login_or_email.'\'
					) and ID_DICTIONARY=(
						select ID_DICTIONARY from CM$DICTIONARY
						where SHORT_NAME=\'EMAIL\'
					)';
				$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, null, true);
				if (!$result) {
					return false;
				} else {
					$email = $result['EMAIL'];
				}
			}
			
			return ['PASSWORD_RESET_TOKEN' => $password_restore_token, 'LOGIN' => $login, 'EMAIL' => $email];
		}
		
		/// Проверить валидность токена сброса пароля
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $login			LOGIN
		 *	@param string $token			PASSWORD_RESET_TOKEN
		 *	@retval bool					Возвращает true, если валиден, иначе false
		 */
		public function checkPasswordResetToken($login, $token) {
			$this->newSanitize($login);
			$this->sanitize($token);
			
			$sql = 'select PASSWORD_RESET_TOKEN from CM$DB_USER where LOGIN='.$login;
			$result = $this->getQueryFirstRowResultWithErrorNoticing($sql, null, true);
			if (!$result) {
				return false;
			} else {
				return password_verify($token, $result['PASSWORD_RESET_TOKEN']);
			}
		}
		
		/// Задать новый пароль, используя токен сброса пароля
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $login			LOGIN
		 *	@param string $token			PASSWORD_RESET_TOKEN
		 *	@param string $newPassword		Новый пароль
		 *	@retval bool					Возвращает true, если успешно, иначе false
		 */
		public function setNewPasswordUsingResetPasswordToken($login, $token, $newPassword) {
			if (!$this->checkPasswordResetToken($login, $token)) {
				return false;
			}
			$this->newSanitize($login);
			$this->sanitize($newPassword);
			
			$sql =
			'update CM$DB_USER set
				PASSWORD=\''.password_hash($newPassword, PASSWORD_BCRYPT).'\',
				NUM_FAILED_ATTEMPTS=NULL,
				LAST_FAILED_ATTEMPT=NULL,
				UNLOCK_AT=NULL,
				PASSWORD_RESET_TOKEN=NULL,
				ID_DB_USER_MODIFIED=ID_DB_USER
			where LOGIN='.$login;
			
			$this->getQueryResultWithErrorNoticing($sql);
			return true;
		}

# ==== КОНЕЦ ВОССТАНОВЛЕНИЕ ПАРОЛЯ ==== #
# =================================================== #
		
	}
?>