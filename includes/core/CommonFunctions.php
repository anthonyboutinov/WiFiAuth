<?php
	
	///	Статический класс с общими полезными методами, использующимися на всем портале
	/**
	 *	@author Anthony Boutinov
	 */
	class CommonFunctions {
		
		///	Эквивалент SQL функции NVL (NullValue)
		/**
		 *	Возвращает переданное значение если оно есть либо второй параметр,
		 *	если значение === NULL. По умолчанию, возвращает пустую строку,
		 *	если переданного значения не существует.
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param mixed $value				Входное значение, которое может не существовать (=== null)
		 *	@param mixed $replacement		(Опционально) Значение, которое подставить, если первый параметр окажется null. По умолчанию, пустая строка
		 *	@retval mixed					Возвращаемое значение
		 */
		public static function NVL($value, $replacement = '') {
			return $value === null ? $replacement : $value;
		}
		
		///	Получить из данного массива его в виде текстовой строки с заданным форматированием
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param array $array							Массив
		 *	@param bool $doKeys							(Опционально) Выводить ли названия ключей. По умолчанию, НЕТ
		 *	@param bool $wrapTopMostArray				(Опционально) Оборачивать ли скобками корневой массив. По умолчанию, ДА
		 *	@param bool $hugValues						(Опционально) Оборачивать ли кавычками значения, если они не являются числовыми. По умолчанию, ДА
		 *	@param string $wrapperLeft					(Опционально) Вид левой  скобки, оборачивающей массив
		 *	@param string $wrapperRight					(Опционально) Вид правой скобки, оборачивающей массив
		 *	@param string $valueHuggerLeft				(Опционально) Вид левых кавычек, оборачивающих значения
		 *	@param string $valueHuggerRight				(Опционально) Вид правых кавычек, оборачивающих значения
		 *	@param string $keyHuggers					(Опционально) Вид кавычек, оборачивающих ключи
		 *	@param string $keyFollowers					(Опционально) Вид разделителя между ключом и значением, например " => "
		 *	@param string $keyValuePairWrapperLeft		(Опционально) Вид левой  скобки, оборачивающей пару ключ-значение
		 *	@param string $keyValuePairWrapperRight		(Опционально) Вид правой скобки, оборачивающей пару ключ-значение
		 *	@param bool $isTopmost						(Опционально) Является ли текущий массив корневым, внутри которого находятся вложенные массивы (всегда true, значение меняется только при рекурсивном выполнении функции)
		 *
		 *	@retval string								Строка с содержимым массива в заданном форматировании
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
		
		///	Полуить из таблицы (2D массива) массив, выбрав только одну колонку
		/**
		 *	Из заданного массива с результатами SQL запроса получить
		 *	упрощенный массив, где значением выбирается только одна колонка 
		 *	таблицы с ключом по некоторой другой колонке таблицы (если указано).
		 *	
		 *	@author Anthony Boutinov
		 *	
		 *	@param array $array							Массив
		 *	@param string $valueSubKey					Название колонки, которую использовать в качестве значений массива
		 *	@param string $newKeyFromValueSubKey		Название колонки, которую использовать в качестве ключей массива. По умолчанию, остаются те ключи, которые были в первоначальном массиве (ничего, если неассоциативный массив или прежние значения ассоциативного массива).
		 *	@retval array
		 */
		public static function extractSingleValueFromMultiValueArray($array, $valueSubKey, $newKeyFromValueSubKey = null) {
			$out = array();
			foreach ($array as $key => $value) {
				$k = $newKeyFromValueSubKey === null ? $key : $value[$newKeyFromValueSubKey];
				$out[$k] = $value[$valueSubKey];
			}
			return $out;
		}
		
		///	Перенаправить на заданную страницу
		/**
		 *	@author Anthony Boutinov
		 *	@param string $page			Название страницы
		 */
		public static function redirect($page) {
			$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";
			header("Location: $base_url/$page");
			exit();
		}
		
		///	Начинается ли строка $haystack со строчки $needle
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $needle			Строка, которую ищем
		 *	@param string $haystack			Строка, в которой ищем
		 *	@retval bool
		 */
		public static function startsWith($needle, $haystack) {
		    // search backwards starting from haystack length characters from the end
		    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}
		
		///	Оканчивается ли строка $haystack строчкой $needle
		/**
		 *	@author Anthony Boutinov
		 *	
		 *	@param string $needle			Строка, которую ищем
		 *	@param string $haystack			Строка, в которой ищем
		 *	@retval bool
		 */
		public static function endsWith($needle, $haystack) {
		    // search forward starting from end minus needle length characters
		    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
		}
		
		///	Проверяет, является ли user agent старым Андроидом
		/**
		 *	Check to see it the user agent is Android and if so then
		 *	check the version number to see if it is lower than 4.0.0
		 *	or passed parameter
		 *
		 *	@author https://gist.github.com/Abban

		 *	@param  string $version			(Опционально) Версия. По умолчанию, 4.0.0
		 *	@retval bool
		 */
		public static function isOldAndroid($version = '4.0.0'){
			if(strstr($_SERVER['HTTP_USER_AGENT'], 'Android')){
				preg_match('/Android (\d+(?:\.\d+)+)[;)]/', $_SERVER['HTTP_USER_AGENT'], $matches);
				return version_compare($matches[1], $version, '<=');
			}
		}
		
		/// Поддерживает ли браузер современный CSS
		/**
		 *	@author Anthony Boutinov
		 *	@retval bool
		 */
		public static function supportsModernCSS() {
			return !CommonFunctions::isOldAndroid('2.3');
		}
		
		/// Сгенерировать случайную строку заданной длины
		/**
		 *	@author Stephen Watkins http://stackoverflow.com/users/151382/stephen-watkins
		 *	
		 *	@param int $length			(Опционально) Требуемая длина. По умолчанию, 10
		 *	@retval string				Строка вида [a-zA-Z0-9]+
		 */
		public static function generateRandomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}
	}

?>