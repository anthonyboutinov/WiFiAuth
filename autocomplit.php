<?php
	require 'includes/core/db_config.php';
	require 'includes/core/DBWiFiInterface.php';

 	$database = new DBWiFiInterface($servername, $username, $password, $dbname,'','','','','');
 	$tokens = $database->getAccessTokenForVKMessage();
 	if ($tokens->num_rows > 0) {
		while($row = $tokens->fetch_assoc()) {
			$birthday = $database->getVKBirthdayUsers($row['ID_USER']);
			if($birthday->num_rows > 0){
				while($rows = $birthday->fetch_assoc()) {

					$domain = substr($rows['LINK'],15);
					$url = 'https://api.vk.com/method/messages.send?domain='.$domain
					.'&message='.urlencode($row['MESSAGE'])
					.'&v=5.34&access_token='.$row['TOKEN'];

				 	if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					$json = curl_exec($curl);
					curl_close($curl);
					}

				}
			}
		}
	}

	$tokens = $database->getMobileParametersForSend();
		 	if ($tokens->num_rows > 0) {
				while($row = $tokens->fetch_assoc()) {
					$phones = $database ->getMobileUsers($row['ID_USER']);
						if($phones->num_rows > 0){
							while($rows = $phones->fetch_assoc()) {
								$url = 'http://sms.ru/sms/send?api_id=
								699b26d8-aa69-53d4-1dfe-d5105fbe37e5&to='
								.$rows['NAME'].'&text='.urlencode($row['MESSAGE']);
								echo '<br>'.$url;
						  		if( $curl = curl_init() ) {
									curl_setopt($curl, CURLOPT_URL, $url);
									curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
									$out = curl_exec($curl);
									echo $out;
									
								}

							}
						}
				}
			}
			curl_close($curl);
?>