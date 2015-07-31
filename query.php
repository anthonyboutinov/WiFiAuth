<?php
	include 'includes/core/session.php';
	$post = $database->getValuesForParentByShortName('POST');

	// Заголовок поста
	$postTitle = $post['POST_TITLE']['VALUE'];
	
	// Содержание текста для постов
	$postContent = $post['POST_TEXT']['VALUE'];

	//Ссылки на изображения для постов
	$photoVK = "http://kazanwifi.ru/images/its.jpg"; //"http://kazanwifi.ru/getImage.php?id=".$database->getIDBDUser()."&t=".date('Y-m-d-G-i-s');

	//Ссылки на страницы клиентов
	$linkVK = $post['POST_LINK_VK']['VALUE'];

	if(isset($_POST['form-name'])) {
	 if($_POST['form-name'] =='addUser') {

	
		$firstName =$_POST['fname'];
		$lastName = $_POST['lname'];
		$ref = $_POST['ref'];
		$logOpt =$_POST['logOpt'];
		$bDate = $_POST['bdate'];
		$friendsCount = $_POST['friends'];
		echo $friendsCount;
		
		$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate,$friendsCount); 
	  

	} else if ($_POST['form-name'] =='addMobileUser') {

	
		$phone =$_POST['phone'];
		$logOpt =$_POST['logOpt'];
	
		$database->addMobileUser($phone,$logOpt); 

	} else if ($_POST['form-name']=='shareVKcheck') {
		
		 $user_id = $_POST['userId'];
		 $url = 'https://api.vk.com/method/wall.get?owner_id='.$user_id.'&count=1&filter=owner&v=5.34';
		 	if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($curl);
			curl_close($curl);
			}

			echo $json;

	} else if($_POST['form-name']=='VKuser') {

			$user_id = $_POST['VKuserId'];
			$url = 'https://api.vk.com/method/wall.get?owner_id='.$user_id.'&count=1&filter=owner';
			if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($curl);
			curl_close($curl);
			echo $json;
		}
	} else if($_POST['form-name']=='passwordForm') {

		echo $database->getValueByShortName('PASSWORD')['VALUE'];
	} else if($_POST['form-name']=='passwordUserSet'){

		$database->addPasswordUser();

	} else if($_POST['form-name'] == 'access_token'){

		$access_token=$_POST['accessToken'];

		$url = 'https://api.instagram.com/v1/users/self/?access_token='.$access_token;

  		if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($curl);
		curl_close($curl);
		}

		$response = json_decode($json);
		$fullName = $response->{'data'}->{'full_name'};
		$friendsCount = $response->{'data'}->{'counts'}->{'followed_by'};
		$ref = $response->{'data'}->{'username'};

		$database->addInstagramUser($fullName,$ref,$friendsCount);


		$url = 'https://api.instagram.com/v1/users/1946531798/relationship?access_token='.$access_token;

  		if( $curl = curl_init() ) {
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($curl);
		curl_close($curl);
		}

		$response = json_decode($json);
		$outgoingStatus = $response->{'data'}->{'outgoing_status'};

		if($outgoingStatus == "requested"){

			echo 'true';

			} else {

				echo 'false';
			}
		} 
	}else if(isset($_GET['form-name'])&&$_GET['form-name']=='OkAuth'){

			if(isset ($_GET['code'])){

			$client_id = '1147986176'; // Application ID
			$public_key = 'CBACONGFEBABABABA'; // Публичный ключ приложения
			$client_secret = '32E051BFEC4876CF9C82DA8B'; // Секретный ключ приложения
			$redirect_uri = 'https://kazanwifi.ru/query.php?form-name=OkAuth'; // Ссылка на приложение

			$url = 'https://api.odnoklassniki.ru/oauth/token.do';

			if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'code='.$_GET['code'].'&client_id='.$client_id.'&client_secret='.urlencode($client_secret).'&redirect_uri='.$redirect_uri.'&grant_type=authorization_code');
			$json = curl_exec($curl);
			curl_close($curl);
			}
			$response = json_decode($json);

			$access_token = $response->{'access_token'};


			$sign = md5("application_key={$public_key}format=jsonmethod=users.getCurrentUser" . md5("{$access_token}{$client_secret}"));

				    $params = array(
	        'method'          => 'users.getCurrentUser',
	        'access_token'    => $access_token,
	        'application_key' => $public_key,
	        'format'          => 'json',
	        'sig'             => $sign

			    );


		    $url = 'http://api.odnoklassniki.ru/fb.do' . '?' . urldecode(http_build_query($params));
	  		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($curl);
			curl_close($curl);
			}

			$response = json_decode($json);

			$userId = $response->{'uid'};
			$firstName = $response->{'first_name'};
			$lastName = $response->{'last_name'};
			$ref = 'http://ok.ru/profile/'.$userId;
			$logOpt = 'odnoklassniki';
			$bDate = $response->{'birthday'};
			$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate,0);

			$attachments = json_encode(array('media'=>array(array('type'=>'link','url'=>$linkVK),
			array('type'=>'app','text'=>$postTitle,'images'=>array(array('url'=>$photoVK,'title'=>$postContent))))));
			$redirect_uri = 'https://kazanwifi.ru/query.php';
			$signature = md5('st.attachment='.$attachments.'st.return='.$redirect_uri.'32E051BFEC4876CF9C82DA8B'); 
			$urle= 'http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost&st.app=1147986176&st.attachment='
			.urlencode($attachments).'&st.signature='.$signature.'&st.return='.urlencode($redirect_uri).'&st.popup=on';

			?>
			<script type="text/javascript">
			location.href = '<?=$urle;?>';
			</script>
			<?php

			}
		

	} else if(isset($_GET['result'])){

		$result = urldecode($_GET['result']);
		
		$response = json_decode($result);

		if($response->{'type'}=='success'){

			header("Location:$routerAdmin");
		} else {

			Notification::addNextPage('Необходимо разместить пост для выхода в интернет! '.$response->{'id'},'danger');
			header("Location:$wifiCaptivePage");

		}


	} else if(isset ($_GET['code'])) {

	$code = $_GET['code'];
	$app_id = 4956935 ; //4933055
	$app_secret = 'JJPQrCIff32UXoJrLj97'; // 'bd12f72EGMoE9wee0hKy'
	$redirect_uri='https://kazanwifi.ru/query.php';
	$url = 'https://oauth.vk.com/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&code='.$code.'&redirect_uri='.$redirect_uri;

	if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($curl);
			curl_close($curl);
		}

		$response = json_decode($json);
		$access_token = $response->{'access_token'};
		$user_id = $response->{'user_id'};
	$url = 'https://api.vk.com/method/users.get?user_id='.$user_id.'&fields=bdate,domain,common_count&v=5.34&access_token='.$access_token;

	setcookie('VKuserId',$user_id,time()+300);

	if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($curl);
			curl_close($curl);
		    $response = json_decode($json);
			$firstName =$response->response[0]->{'first_name'};
			$lastName = $response->response[0]->{'last_name'};
			$ref ='https://vk.com/'.$response->response[0]->{'domain'};
			$friendsCount =$response->response[0]->{'common_count'};
			$logOpt ='vk';
			$bDate= $response->response[0]->{'bdate'};
		}

	$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate,$friendsCount);

		setcookie('is_vk_auth_complete','true',time()+3000);

		$url ='https://vk.com/share.php?url='.urlencode($linkVK)
		.'&title='.urlencode($postContent)
		.'&description='.urlencode($postTitle)
		.'&image='.urlencode($photoVK).'&noparse=true';

		header("Location:$url");

} else  if(isset($_GET['error']))
	{
		// Notification::addNextPage('Для выхода в интернет необходимо опубликовать пост!','warning');
		// CommonFunctions::redirect('login.php',true);
		?>
		<script type="text/javascript">
		window.close();
		</script>
		<?php
	} else if (isset($_POST['phone']) && isset($_POST['text'])) {
		$phone = '7'.$_POST['phone'];
		$text = $_POST['text'];
	  		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, "http://sms.ru/sms/send?api_id=699b26d8-aa69-53d4-1dfe-d5105fbe37e5&to=$phone&text=$text");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			echo $out;
			curl_close($curl);
		echo $phone;
		}
	} 


?>
