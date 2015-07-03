<?php 
	$phone = $_POST['phone'];
	$pas = $_POST['password'];

	if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, "http://sms.ru/sms/send?api_id=78e1649e-035d-8254-4d7b-5608df83693e&to=$phone&text=$pas");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		$out = curl_exec($curl);
		echo $out;
		curl_close($curl);
	}
?>