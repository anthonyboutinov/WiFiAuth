$(document).ready(function() {	
	
/* *
   * Фокусировка на полях логин/пароль
 */
				
	function reorderToLogin() {
		$("#login").css('z-index', '5');
		$("#password").css('z-index', '4');
	}
	
	function reorderToPassword() {
		$("#login").css('z-index', '4');
		$("#password").css('z-index', '5');
	}
	
	reorderToLogin();
	$(loginField).focusin(reorderToLogin);
	$("#password").focusin(reorderToPassword);
	
	// Сфокусироваться на нужном поле при загрузке страницы
	var loginField = $("#login");
	if ($(loginField).attr('value') == '') {
		$(loginField).focus();
	} else {
		$("#password").focus();
		reorderToPassword();
	}
	
/* *
   * Модал Забыл пароль
 */
	
	
	
});