<script type="text/javascript" src="//api.ok.ru/js/fapi5.js" defer="defer"></script>
<?php
// $url = 'http://www.odnoklassniki.ru/oauth/authorize?client_id=1147986176&scope=VALUABLE_ACCESS&response_type=code&redirect_uri=https://kazanwifi.ru/postoOk.php?form-name=12312&layout=w'; header('Location:$url');
 $url = 'https://api.odnoklassniki.ru/oauth/token.do';

			if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'code='.$_GET['code'].'&client_id=1147986176&client_secret=32E051BFEC4876CF9C82DA8B&redirect_uri=https://kazanwifi.ru/postoOk.php?&grant_type=authorization_code');
			$json = curl_exec($curl);
			curl_close($curl);
			//echo $json;
		}
$response = json_decode($json);
$attachments = json_encode(array('place_id'=>570702519740, 'media'=>array(
	array('type'=>'text','text'=>'Hello!'))));
$redirect_uri = 'https://kazanwifi.ru/postoOk.php';
$signature = md5('st.attachment='.$attachments.'st.return='.$redirect_uri.'32E051BFEC4876CF9C82DA8B'); //tkn1YGqvNZ2btSDRUnYqrDu0696n0gV4v4nVgVeSa8IQmEpYzLPXHtvDTe4e6z5mMmaPF
$url = 'http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost&st.app=1147986176&st.attachment='
.urlencode($attachments).'&st.signature='.$signature.'&st.return='.urlencode($redirect_uri).'&st.popup=on';
echo $url;
?>