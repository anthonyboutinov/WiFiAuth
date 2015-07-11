<?php
	include 'includes/core/session.php';


	if(isset($_POST['form-name'])) {
	 if($_POST['form-name'] =='addUser') {

	
		$firstName =$_POST['fname'];
		$lastName = $_POST['lname'];
		$ref = $_POST['ref'];
		$logOpt =$_POST['logOpt'];
		$bDate= $_POST['bdate'];
		
		$database->addUser($firstName,$lastName,$ref,$logOpt,$bDate); 
	  

	} else if ($_POST['form-name'] =='addMobileUser') {

	
		$phone =$_POST['phone'];
		$logOpt =$_POST['logOpt'];
	
		$database->addMobileUser($phone,$logOpt); 
	} 
	// else if($_POST['form-name']=='shareVK'){

	// 		$url = $_POST['site'];
	// 		$title = $_POST['title'];
	// 		$description = $_POST['description'];
	// 		$image = $_POST['image'];
	// 		$noparse = $_POST['noparse'];

	// 		if( $curl = curl_init() ) {
	// 		curl_setopt($curl, CURLOPT_URL, "http://vk.com/share.php?url=$url&title=$title&description=$description&image=$image&noparse=$noparse");
	// 		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	// 		$out = curl_exec($curl);
	// 		echo $out;
	// 		curl_close($curl);
	// 	}
	// }

}

?>