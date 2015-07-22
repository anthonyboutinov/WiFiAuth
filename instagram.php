<?php

if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, "http://sms.ru/sms/send?api_id=699b26d8-aa69-53d4-1dfe-d5105fbe37e5&to=$phone&text=$text");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			echo $out;
			curl_close($curl);

?>