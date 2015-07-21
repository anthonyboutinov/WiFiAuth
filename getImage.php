<?php
	$current_page_is_not_protected = true;
	include 'includes/core/session.php';
	header("Content-type: image/jpeg");
	if (isset($_GET['id_db_user'])) {
		$timestamp= isset($_GET['t']) ? $_GET['t'] : NULL;
		echo $database->getPostImage($_GET['id_db_user'], $timestamp);
	} else {
		Error::fatalError('Параметры не заданы');
	}
?>
