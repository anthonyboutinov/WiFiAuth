<?php 
	include 'includes/core/session.php';
	
	if (isset($_POST['phone']) && isset($_POST['text'])) {
		$phone = '7'.$_POST['phone'];
		$text = $_POST['text'];
	
		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, "http://sms.ru/sms/send?api_id=78e1649e-035d-8254-4d7b-5608df83693e&to=$phone&text=$text");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			echo $out;
			curl_close($curl);
		}
		echo $phone;
	}
?>