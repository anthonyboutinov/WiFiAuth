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
   * Ограничение вводимых данных
 */

 	$("#login").alphanum({
	 	allow: '-_',
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false
	});

	
/* *
   * Модал Забыл пароль
 */
	
	$("#forget-password-recovery-button").click(function(e) {
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: "admin-login.php",
			data: {
				'form-name': 'forget-password-recovery-screen',
				'login': $("#login").val()
			},
			success: function(msg){
				$("#main-area").html(msg).addClass('animated fadeInUp');
			},
			fail: failNotification
		});
	});
	
	
});