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
					echo $url;
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
?>