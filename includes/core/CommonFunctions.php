<?php
	
	class CommonFunctions {
		
		public static function changeOffsetLink($desktop, $offset) {
			echo '?';
			if ($desktop) {
				echo 'mobile&';
			}
			echo 'offset='.$offset;
		}
		
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
		 *	@return (type)			description
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
		 *	@param ($valueHuggers) (string)					(Опционально) Вид кавычек, оборачивающих значения
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
			$doKeys = false,
			$wrapTopMostArray = true,
			$hugValues = true,
			$wrapperLeft = '[',
			$wrapperRight = ']',
			$valueHuggers = '\'',
			$keyHuggers = '\'', 
			$keyFollowers = ', ',
			$keyValuePairWrapperLeft = '[',
			$keyValuePairWrapperRight = ']',
			$isTopmost = true
		) {
			
			$out = (
				($wrapTopMostArray == true || $isTopmost == false) ?
					$wrapperLeft : ''
			);
			
			$i = 0;
			foreach ($array as $key => $value) {
				
				$localValueHuggers = (
					$hugValues == true ?
						(is_numeric($value) || is_array($value) ? '' : $valueHuggers)
					:
						''
				);
				
				$out = $out.($i++ == 0 ? '' : ', ').(
					$doKeys == true ?
						$keyValuePairWrapperLeft.$keyHuggers.$key.$keyHuggers.$keyFollowers
					:
						''
				).$localValueHuggers.(
					is_array($value) ?
						CommonFunctions::arrayToString(
							$value,
							$doKeys,
							$wrapTopMostArray,
							$hugValues,
							$wrapperLeft,
							$wrapperRight,
							$valueHuggers,
							$keyHuggers,
							$keyFollowers,
							$keyValuePairWrapperLeft,
							$keyValuePairWrapperRight,
							false
						)
					:
						$value
				).$localValueHuggers.(
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
		
		
	}

?>