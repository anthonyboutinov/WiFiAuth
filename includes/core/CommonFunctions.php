<?php
	
	class CommonFunctions {
		
		public static function changeOffsetLink($desktop, $offset) {
			echo '?';
			if ($desktop) {
				echo 'mobile&';
			}
			echo 'offset='.$offset;
		}
		
	}

?>