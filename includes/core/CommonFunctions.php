<?php
	
	class CommonFunctions {
		
		public static function changeOffsetLink($desktop, $offset) {
			echo '?';
			if ($desktop) {
				echo 'mobile&';
			}
			echo 'offset='.$offset;
		}
		
		public static function NVL($value, $replacement = '') {
			return $value === null ? $replacement : $value;
		}
		
		public static function arrayToString(
			$array,
			$doKeys = false,
			$wrapperLeft = '(',
			$wrapperRight = ')',
			$valueHuggers = '\'',
			$keyHuggers = '\'', 
			$keyFollowers = ', ',
			$keyValuePairWrapperLeft = '[',
			$keyValuePairWrapperRight = ']'
		) {
			
			$out = $wrapperLeft;
			$i = 0;
			foreach ($array as $key => $value) {
				$out = $out.($i++ == 0 ? '' : ', ').($doKeys ? $keyValuePairWrapperLeft.$keyHuggers.$key.$keyHuggers.$keyFollowers : '').$valueHuggers.$value.$valueHuggers.($doKeys ? $keyValuePairWrapperRight : '');
			}
			return $out.$wrapperRight;
		}
		
		public static function arrayToJSONString($array) {
			return CommonFunctions::arrayToString($array, true, '', '', '');
		}
		
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