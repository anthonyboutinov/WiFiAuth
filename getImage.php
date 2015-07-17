<?php
	$current_page_is_not_protected = true;
	include 'includes/core/session.php';
	header("Content-type: image/jpeg");
	if (isset($_GET['id'])) {
		echo $database->getPostImage($_GET['id']);
	} else {
		Error::fatalError('Параметры не заданы');
	}
?>
