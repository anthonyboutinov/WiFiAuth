<?php
	include 'includes/core/session.php';
	$post = $database->getValuesForParentByShortName('POST');

	// Заголовок поста
	$postTitle = $post['POST_TITLE']['VALUE'];
	
	// Содержание текста для постов
	$postContent = $post['POST_TEXT']['VALUE'];

	//Ссылки на изображения для постов
	$photoVK = "https://kazanwifi.ru/getImage.php?id=".$database->getIDBDUser()."&t=".date('Y-m-d-G-i-s');

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
