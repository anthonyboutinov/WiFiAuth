// Функция генерации пароля
$.extend({
  password: function (length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
            // Исключать неоднозначные символы: O, o, 0, I, i ()
            if (randomNumber == 111 || randomNumber == 79 || randomNumber == 48 || randomNumber == 73 || randomNumber == 105) { continue; }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
  }
});

$(document).ready(function() {
	
	// включить подсказки
	$('[data-toggle="tooltip"]').tooltip({'html': true});
	
	/* *
	   * ГЕНЕРИРОВАНИЕ ПАРОЛЕЙ
	   */

	var generateTokenButton = $("#generate-token");
	var generatePasswordButton = $("#generate-password");
	var generateLoginButton = $("#generate-login");
	
	function genRouterToken() {
		$("#router-token").val($.password(16,true));
	}
	$(generateTokenButton).click(genRouterToken);
	genRouterToken();
	
	function genRouterLogin() {
		var login = $.password(10,false);
		$("#router-login").val(login.toLowerCase());
	}
	$(generateLoginButton).click(genRouterLogin);
	genRouterLogin();	

	function genPassword() {
		var password = $.password(4,false)+'-'+$.password(4,false)+'-'+$.password(4,false);
		$("#password").val(password.toUpperCase());
	}
	$(generatePasswordButton).click(genPassword);
	genPassword();


    $("[data-id='disabled']").click(function (e) {
        e.preventDefault();
        openmodalFormAndFocusOn("#disable-password");

        var idUser;
        var password;
        idUser = $(this).attr("data-id-db-user");
        $('#enableModal').modal('show');
        
        $('#activeClient').click( function() {
	        password =  $('#enable-password').val();
	        if(password){
		        $.ajax({
		            type: "POST",
		            url: "superadmin-query.php",
		            data:{ 
		                'password': password,
		                'form-name': 'superadmin-confirm'
		            },
		            success: function(msg){
		                if (msg == 'true' ) {
							$.ajax({
		                        type: "POST",
		                        url: "superadmin-query.php",
		                        data:{ 
		                            'idUser': idUser, 
		                            'active':'T', 
		                            'form-name': 'enable-disable-user'
		                        },
		                        success: function(msg){
	// 	                            setTimeout(function(){location.reload();}, 600);
									location.reload();
		                        },
		                        fail: failNotification
	                        }); 
		                } else {
		                    addNotification('Неверный пароль, попробуйте еще раз!', 'danger');
		                }
		            }
		        });
	        } else {
				addNotification('Введите пароль!', 'warning');
            }
        });
    }).mouseenter(function() {
        $(this).find("i").removeAttr('class').addClass('fa fa-dot-circle-o');
    }).mouseleave(function() {
        $(this).find("i").removeAttr('class').addClass('fa fa-circle-o');
    });
    
    $("[data-id='enabled']").click(function (e) {
        e.preventDefault();
        openmodalFormAndFocusOn("#enable-password");

        var idUser;
        var password;
        idUser = $(this).attr("data-id-db-user");
        $('#disableModal').modal('show');
        $('#disactiveClient').click( function() {
	        password =  $('#disable-password').val();
	        if(password){
		        $.ajax({
	                type: "POST",
	                url: "superadmin-query.php",
	                data:{ 
	                    'password': password,
	                    'form-name': 'superadmin-confirm'
	                },
	                success: function(msg){
	                    if (msg == 'true' ) {
							$.ajax({
	                            type: "POST",
	                            url: "superadmin-query.php",
	                            data:{ 
	                                'idUser': idUser, 
	                                'active':'F', 
	                                'form-name': 'enable-disable-user'
	                            },
	                            success: function(msg){
		                            location.reload();
	//                                 setTimeout(function(){location.reload();}, 600);
	                            },
		                        fail: failNotification
	                        }); 
	                    } else {
	                        addNotification('Неверный пароль, попробуйте еще раз!', 'danger');
	                    }
		            }
	            }); 
	        } else {
	            addNotification('Введите пароль!', 'warning');
	        }
        });
    }).mouseenter(function() {
        $(this).find("i").removeAttr('class').addClass('fa fa-times-circle');
    }).mouseleave(function() {
		$(this).find("i").removeAttr('class').addClass('fa fa-circle');
    });
    
    
    
/* *
   * ВЕРТИКАЛЬНОЕ ПОЗИЦИОНИРОВАНИЕ
 */

	var modalForm1 = $("#disableModal");
	var modalForm2 = $("#enableModal");
	
	// Функции
	
		function positionVertically() {
			if ($(panel).outerHeight() < $(window).height()) {
				$(panel).css('margin-top', ($(window).height() - $(panel).outerHeight()) / 2);
				$(footer).css('position', 'fixed');
			} else {
				$(panel).css('margin-top', 0);
				$(footer).css('position', 'inherit');
			}	
			$(modalForm1).css('margin-top', ($(window).height() - $(modalForm1).outerHeight()) / 2);
			$(modalForm2).css('margin-top', ($(window).height() - $(modalForm2).outerHeight()) / 2);
		}
						
		function openmodalFormAndFocusOn(focusOn) {
			setTimeout(positionVertically, 200);
			$(focusOn).focus();
		}
	
	// EOF Функции
	
	// Привязки к дейсвтиям
	
		var panel = $(".glass-panel");
		var footer = $("footer.footer");

		setTimeout(positionVertically, 200);
		$(window).resize(positionVertically);
	
	// EOF Привязки к дейсвтиям

});