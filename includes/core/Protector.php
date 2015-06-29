<?php

class Protector {
	
	protected $database;
	
	public $adminMainPage 		= 'admin-dashboard.php';
	public $superadminMainPage 	= 'superadmin-clients.php';
	
	function __construct($database) {
		$this->database = $database;
	}
	/**
	 *	protectPageSetMinAccessLevel
	 *
	 *	Устанавливает минимальный уровень доступа для страницы superadmin.
	 *	Если пользователь не соответсвует уровню доступа, то он перенаправляется
	 *	на главную страницу (admin-главная или superadmin-главная).
	 *	
	 *	@author Anthony Boutinov
	 *	
	 *	@param ($acc_level_short_name) (string)	Короткое название уровня доступа, необходимого для доступа к это странице
	 */
	public function protectPageSetMinAccessLevel($acc_level_short_name) {
		// Если не суперадмин
		// или если суперадмин, но не соответствуюет заданному уровню доступа,
		// то редиректнуть на начальную страницу
		if (!$this->database->is_superadmin()) {
			CommonFunctions::redirect($this->adminMainPage);
// 			Notification::add("redirect to: ".$this->adminMainPage);
// 			echo ("redirect to: ".$this->adminMainPage);
		} else if ($this->database->is_superadmin() && !$this->database->meetsAccessLevel($acc_level_short_name)) {
			CommonFunctions::redirect($this->superadminMainPage);
// 			Notification::add("redirect to: ".$this->superadminMainPage);
// 			echo ("redirect to: ".$this->superadminMainPage);
		}
	}
	
	/**
	 *	protectPageAdminPage
	 *
	 *	Защищает страницу admin от неавторизованного входа со стороны
	 *	суперадмина (суперадмин должен «притвориться» админом, чтобы попасть
	 *	на эту страницу)
	 *	
	 *	@author Anthony Boutinov
	 */
	public function protectPageAdminPage() {
		if (!$this->database->is_db_user()) {
			if ($this->database->is_superadmin()) {
				CommonFunctions::redirect($this->superadminMainPage);
// 				Notification::add("redirect to: ".$this->superadminMainPage);
// 				echo ("redirect to: ".$this->superadminMainPage);
			} else {
				CommonFunctions::redirect($this->adminMainPage);
// 				Notification::add("redirect to: ".$this->adminMainPage);
// 				echo ("redirect to: ".$this->adminMainPage);;
			}
		}
	}
	
	public function protectPageForbidSuperadmin() {
		if ($this->database->is_superadmin()) {
			CommonFunctions::redirect($this->superadminMainPage);
		}
	}
	
}
	
?>