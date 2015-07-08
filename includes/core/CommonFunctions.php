<?php
	
	/**
	 *	class CommonFunctions
	 *
	 *	Статический класс с общими полезными методами, использующимися на портале.
	 *	
	 *	@author Anthony Boutinov
	 */
	class CommonFunctions {
		
		/**
		 *	changeOffsetLink
		 *
		 *	Получить окончание ссылки для нового значения offset с учетом
		 *	того, необходимо ли указывать, что страница загружается в мобильном
		 *	или десктопном виде.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($is_desktop) (bool)	Загружать десктопную или мобильную версию страницы (добавлять или нет пустую константу mobile)
		 *	@param ($offset) (number)	Новое значение сдвига таблицы
		 *	@return (string)			description
		 */
/*
		public static function changeOffsetLink($is_desktop, $offset) {
			$out = '?';
			if (!$is_desktop) {
				$out = $out.'mobile&';
			}
			return $out.'offset='.$offset;
		}
*/
		
		/**
		 *	NVL
		 *
		 *	Эквивалент SQL функции NVL (NullValue). Возвращает переданное
		 *	значение если оно есть либо второй параметр, если значение === NULL.
		 *	По умолчанию, возвращает пустую строку, если переданного значения
		 *	не существует.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($value) (mixed)			Входное значение, которое может не существовать (=== null)
		 *	@param ($replacement) (mixed)	(Опционально) Значение, которое подставить, если первый параметр окажется null
		 *	@return (mixed)					Возвращаемое значение
		 */
		public static function NVL($value, $replacement = '') {
			return $value === null ? $replacement : $value;
		}
		
		/**
		 *	arrayToString
		 *
		 *	Функция, которая по данному массиву возвращает его в виде текстовой
		 *	строки с заданным форматированием.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($array) (mixed array)					Массив
		 *	@param ($doKeys) (bool)							(Опционально) Выводить ли названия ключей. По умолчанию, НЕТ
		 *	@param ($wrapTopMostArray) (bool)				(Опционально) Оборачивать ли скобками корневой массив. По умолчанию, ДА
		 *	@param ($hugValues) (bool)						(Опционально) Оборачивать ли кавычками значения, если они не являются
		 *														числовыми. По умолчанию, ДА
		 *	@param ($wrapperLeft) (string)					(Опционально) Вид левой  скобки, оборачивающей массив
		 *	@param ($wrapperRight) (string)					(Опционально) Вид правой скобки, оборачивающей массив
		 *	@param ($valueHuggerLeft) (string)				(Опционально) Вид левых кавычек, оборачивающих значения
		 *	@param ($valueHuggerRight) (string)				(Опционально) Вид правых кавычек, оборачивающих значения
		 *	@param ($keyHuggers) (string)					(Опционально) Вид кавычек, оборачивающих ключи
		 *	@param ($keyFollowers) (string)					(Опционально) Вид разделителя между ключом и значением, например " => "
		 *	@param ($keyValuePairWrapperLeft) (string)		(Опционально) Вид левой  скобки, оборачивающей пару ключ-значение
		 *	@param ($keyValuePairWrapperRight) (string)		(Опционально) Вид правой скобки, оборачивающей пару ключ-значение
		 *	@param ($isTopmost) (bool)						(Опционально) Является ли текущий массив корневым, внутри которого
		 *														находятся вложенные массивы (всегда true, значение меняется только
		 *														при рекурсивном выполнении функции)
		 *
		 *	@return (string)								Строка с содержимым массива в заданном форматировании
		 */
		public static function arrayToString(
			$array,
			$doKeys						= false,
			$wrapTopMostArray			= true,
			$hugValues					= true,
			$wrapperLeft				= null,
			$wrapperRight				= null,
			$valueHuggerLeft			= null,
			$valueHuggerRight			= null,
			$keyHuggers					= null,
			$keyFollowers				= null,
			$keyValuePairWrapperLeft	= null,
			$keyValuePairWrapperRight	= null,
			$isTopmost					= true
		) {
			
			// default values
			if ($wrapperLeft == null) {
				$wrapperLeft 							= '[';
			}
			if ($wrapperRight == null) {
				$wrapperRight 							= ']';
			}
			if ($valueHuggerLeft == null) {
				$valueHuggerLeft 						= '\'';
			}
			if ($valueHuggerRight == null) {
				$valueHuggerRight 						= '\'';
			}
			if ($keyHuggers == null) {
				$keyHuggers 							= '\'';
			}
			if ($keyFollowers == null) {
				$keyFollowers 							= ',';
			}
			if ($keyValuePairWrapperLeft == null) {
				$keyValuePairWrapperLeft 				= '[';
			}
			if ($keyValuePairWrapperRight == null) {
				$keyValuePairWrapperRight 				= ']';
			}
			
			
			$out = (
				($wrapTopMostArray == true || $isTopmost == false) ?
					$wrapperLeft : ''
			);
			
			$i = 0;
			foreach ($array as $key => $value) {
				
				$localValueHuggers = (
					$hugValues == true ?
						(is_numeric($value) || is_array($value) ? ['', ''] : [$valueHuggerLeft, $valueHuggerRight])
					:
						['', '']
				);
				
				$out = $out.($i++ == 0 ? '' : ',').(
					$doKeys == true ?
						$keyValuePairWrapperLeft.$keyHuggers.$key.$keyHuggers.$keyFollowers
					:
						''
				).$localValueHuggers[0].(
					is_array($value) ?
						CommonFunctions::arrayToString(
							$value,
							$doKeys,
							$wrapTopMostArray,
							$hugValues,
							$wrapperLeft,
							$wrapperRight,
							$valueHuggerLeft,
							$valueHuggerRight,
							$keyHuggers,
							$keyFollowers,
							$keyValuePairWrapperLeft,
							$keyValuePairWrapperRight,
							false
						)
					:
						$value
				).$localValueHuggers[1].(
					$doKeys ? $keyValuePairWrapperRight : ''
				);
			}
			
			$out = $out.(
				($wrapTopMostArray == true || $isTopmost == false) ?
					$wrapperRight : ''
			);
			return $out;
		}
		
		/**
		 *	extractSingleValueFromMultiValueArray
		 *
		 *	Из заданного массива с результатами SQL запроса получить
		 *	упрощенный массив, где значением выбирается только одна колонка 
		 *	таблицы с ключом по некоторой другой колонке таблицы (если указано).
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($array) (mixed array)					Массив
		 *	@param ($valueSubKey) (string)					Название колонки, которую использовать в качестве значений массива
		 *	@param ($newKeyFromValueSubKey) (string)		Название колонки, которую использовать в качестве ключей массива.
		 *														По умолчанию, остаются те ключи, которые были в первоначальном
		 *														массиве (ничего, если неассоциативный массив или прежние значения
		 *														ассоциативного массива).
		 *
		 *	@return (type)			description
		 */
		public static function extractSingleValueFromMultiValueArray($array, $valueSubKey, $newKeyFromValueSubKey = null) {
			$out = array();
			foreach ($array as $key => $value) {
				$k = $newKeyFromValueSubKey === null ? $key : $value[$newKeyFromValueSubKey];
				$out[$k] = $value[$valueSubKey];
			}
			return $out;
		}
		
		/**
		 *	redirect
		 *
		 *	Перенаправляет на заданную страницу.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($page) (string)	название страницы
		 */
		public static function redirect($page) {
			$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
			header("Location: $base_url/$page");
			exit();
		}
		
		/**
		 *	startsWith
		 *
		 *	Проверяет, начинается ли строка $haystack со строчки $needle.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($needle) (string)	Строка, которую ищем
		 *	@param ($haystack) (string)	Строка, в которой ищем
		 *	@return (bool)				Начинается с заданной строки или нет?
		 */
		public static function startsWith($needle, $haystack) {
		    // search backwards starting from haystack length characters from the end
		    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}
		
		/**
		 *	endsWith
		 *
		 *	Проверяет, оканчивается ли строка $haystack строчкой $needle.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param ($needle) (string)	Строка, которую ищем
		 *	@param ($haystack) (string)	Строка, в которой ищем
		 *	@return (bool)				Оканчивается заданной строкой или нет?
		 */
		public static function endsWith($needle, $haystack) {
		    // search forward starting from end minus needle length characters
		    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
		}
		
		/**
		 * Is Old Android
		 *
		 * Check to see it the user agent is Android and if so then
		 * check the version number to see if it is lower than 4.0.0
		 * or passed parameter
		 *
		 *	@author https://gist.github.com/Abban
		 *
		 * @param  string $version
		 * @return boolean
		 */
		public static function isOldAndroid($version = '4.0.0'){
			if(strstr($_SERVER['HTTP_USER_AGENT'], 'Android')){
				preg_match('/Android (\d+(?:\.\d+)+)[;)]/', $_SERVER['HTTP_USER_AGENT'], $matches);
				return version_compare($matches[1], $version, '<=');
			}
		}
		
		public static function supportsModernCSS() {
			return !CommonFunctions::isOldAndroid('2.3');
		}
	}

?>