<?php
	require 'includes/core/db_config.php';
	require 'includes/core/DBWiFiInterface.php';
echo '<br>4';
 $database = new DBWiFiInterface($servername, $username, $password, $dbname,'','','','','');
echo '<br>5';
 $tokens = $database->getAccessTokenForVKMessage();
 	if ($tokens->num_rows > 0) {
		while($row = $tokens->fetch_assoc()) {
echo '<br>10';
			echo $row['ID_USER'];
			echo '<br>'.$row['TOKEN'];
			echo '<br>'.$row['MESSAGE'];
			
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

					echo $json;

				}
			}
		}
	}
?>