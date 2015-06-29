<?php

class Protector {
	
	protected $database;
	
	public $adminMainPage 		= 'admin-dashboard.php';
	public $superadminMainPage 	= 'superadmin-clients.php';
	
	function __construct($database) {
		$this->database = $database;
	}
	
	public function protectPageSetMinAccessLevel($acc_level_short_name) {
		if (!$this->database->is_superadmin() || !$this->database->meetsAccessLevel($acc_level_short_name)) {
			Notification::add("redirect to ".$superadminMainPage);
// 			CommonFunctions::redirect($superadminMainPage);
		}
	}
	
	public function protectPageAdminPage() {
		if (!$this->database->is_db_user()) {
			if ($this->database->is_superadmin()) {
// 				Notification::add("redirect to ".$this->superadminMainPage);
				CommonFunctions::redirect($this->superadminMainPage);
			} else {
// 				Notification::add("redirect to ".$this->adminMainPage);
				CommonFunctions::redirect($this->adminMainPage);
			}
		}
	}
	
}
	
?>