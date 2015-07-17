<?php
	include 'includes/core/session.php';
	$protector->protectPageAdminPage();
	
	if ($database == false) {
		Error::fatalError('DEBUG error in includes/base/admin.php: $database not found');
	}
	
	// Название компании
	$companyName = $database->getValueByShortName('COMPANY_NAME')['VALUE'];
	
	// Постфикс заголовка окна панели управления
	$adminPanelTitle = '— Панель управления '.$companyName;
	
	// Отрисовывать ли на страницу полное содержимое в нормальном виде.
	// Менять на false в нужных блоках в страницах-агрегаторах
	// (Dashboard, в частности).
	$drawFullContent = true;
?>