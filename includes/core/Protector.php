<?php

/// Отвечает за защиту страниц от несанкцианированного доступа (разграничение клиентов и администраторов, уровни доступа)
/**
 *	@author Anthony Boutinov
 */
class Protector {
	
	protected $database; /// Ссылка на объект интерфейса базы данных
	
	public $adminMainPage 		= 'admin-dashboard.php';
	public $superadminMainPage 	= 'superadmin-clients.php';
	
	function __construct($database) {
		$this->database = $database;
	}
	///	Установить минимальный уровень доступа для страницы superadmin
	/**
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
		} else if ($this->database->is_superadmin() && !$this->database->meetsAccessLevel($acc_level_short_name)) {
			CommonFunctions::redirect($this->superadminMainPage);
		}
	}
	
	///	Защитить страницу admin от неавторизованного входа со стороны суперадмина
	/**
	 *	Суперадмин должен «притвориться» админом, чтобы попасть на такую страницу
	 *	
	 *	@author Anthony Boutinov
	 */
	public function protectPageAdminPage() {
		if ($this->database->is_router()) {
			CommonFunctions::redirect($wifiCaptivePageMainPage);
		} else if (!$this->database->is_db_user()) {
			if ($this->database->is_superadmin()) {
				CommonFunctions::redirect($this->superadminMainPage);
			} else {
				CommonFunctions::redirect($this->adminMainPage);
			}
		}
	}
	
	///	Защитить страницу из группы admin-... от доступа любым суперадмином
	/**
	 *	@author Anthony Boutinov
	 */
	public function protectPageForbidSuperadmin() {
		if ($this->database->is_superadmin()) {
			Notification::addNextPage('Вы не имеете право доступа к странице, на которую пытались перейти.', 'warning');
			CommonFunctions::redirect($this->adminMainPage);
		}
	}
	
}
	
?>