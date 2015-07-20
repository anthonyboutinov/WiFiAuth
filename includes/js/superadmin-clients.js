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

/// Включить подсказки
function enableTooltips() {
	// включить подсказки
	$('[data-toggle="tooltip"]').tooltip({'html': true});
	// включить информеры
	$('[data-toggle="popover"]').popover({'html': true});
}

/// Сделать DOM соединения с клиентскими кнопками "вкл/выкл"
function makeClientEnableDisableButtonsDOMConnections() {
	
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
									location.reload();
		                        },
		                        error: function (request, status, error) { failNotification(); }
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
	                            },
		                        error: function (request, status, error) { failNotification(); }
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
}

/// Сделать открытие модуля superadmin-clients-info
function makeOpenClientInfo() {
	$("[data-toggle=\"right-hand-side-info\"]").click(function(e) {
		e.preventDefault(); 
		
		$.ajax({
			type: "GET",
			url: "includes/modules/superadmin-clients-info.php",
			data: {
				'id_client': $(this).attr("data-id-db-user")
			},
			success: function(msg) {
				if (msg == "error") {
					failNotification();
				} else {
					setRightHandSide(msg);
				}
			},
			error: function (request, status, error) { failNotification(); }
		});
	});
}

/// Сделать соединения с элементами DOM superadmin-clients-table
function makeTableDOMConnections() {
	
	hideOrShowLeftHandSidesCollapsableItems();
	makeOpenClientInfo();
	enableTooltips();
	makeClientEnableDisableButtonsDOMConnections();
		
}

/// Сделать соединения по генерированию паролей с элементами DOM superadmin-clients-add-client
function enablePasswordGenerationCapabilities() {

	var generateTokenButton = $("#generate-token");
	var generatePasswordButton = $("#generate-password");
	var generateLoginButton = $("#generate-login");
	
	function genRouterToken() {
		$("#router-token").val($.password(16,false));
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
}

/// Сделать соединения по ограничению вводимых данных с элементами DOM superadmin-clients-add-client
function enableAddClientFieldsRectictions() {

 	$("#login").alphanum({
	 	allow: '-_',
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false
	});
	
	$("#router-login").alphanum({
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false,
	    allowUpper: false
	});
	
	$("input[type='email']").alphanum({
	 	allow: '-_@.',
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false
	});
	
	
	$(".superadmin-clients-popover-container > a").click(function(e) {
		e.preventDefault();
	});
}

/// Сделать соединения с элементами DOM superadmin-clients-add-client
function makeAddClientDOMConnections() {
	enableVerticalPositioning();
	enablePasswordGenerationCapabilities();
	enableAddClientFieldsRectictions();
	makeInfoPanelCloseButtonConnection();
}

function makeInfoPanelCloseButtonConnection() {
	$("#close-right-hand-side").click(function(e) {
		e.preventDefault();
		$("#right-hand-side").html('');
		setTimeout(function() {
			leftHandSideIsCompressed = false;
			hideOrShowLeftHandSidesCollapsableItems();
			$("#left-hand-side").removeClass("col-md-4").addClass("col-md-12");
			setTimeout(function() {
				$("#add-user-button").attr('style', 'dislpay:inline-block');
			}, 1000);
		}, 200);
	});
}

/// Включить возможности сотрировки
function enableSortingCapabilities() {
	function order_by(by, _this) {
		$.ajax({
			type: "GET",
			url: "includes/modules/superadmin-clients-table.php",
			data: {
				'order-by': by
			},
			success: function(msg) {
				$("#table").html(msg);
				setActiveClass(_this);
			},
			error: function (request, status, error) { failNotification(); }
		});
	}
	
	function setActiveClass(_this) {
		$("[id^=\"order-by-\"]").removeClass("active");
		$(_this).addClass("active");
	}

	$("#order-by-id").click(function(e) {
		e.preventDefault();
		order_by('ID_DB_USER', $(this));
	});
	
	$("#order-by-name").click(function(e) {
		e.preventDefault();
		order_by('NAME', $(this));
	});
	
	$("#order-by-traffic").click(function(e) {
		e.preventDefault();
		order_by('TRAFFIC', $(this));
	});
	
}

/// Выполнить вертикальное позиционирование
function positionVertically() {	
	$(modalForm1).css('margin-top', (document.body.clientHeight - $(modalForm1).outerHeight()) / 2);
	$(modalForm2).css('margin-top', (document.body.clientHeight - $(modalForm2).outerHeight()) / 2);
}

/// Открыть модал и сфокусироваться на...				
function openmodalFormAndFocusOn(focusOn) {
	setTimeout(positionVertically, 200);
	$(focusOn).focus();
}

/// Включить вертикальное позиционирование
function enableVerticalPositioning() {
	modalForm1 = $("#disableModal > .modal-dialog");
	modalForm2 = $("#enableModal > .modal-dialog");
	setTimeout(positionVertically, 200);
	$(window).resize(positionVertically);	
}

/// Подготовиться к вертикальному позиционированию
function prepareForVerticalPositioning() {
	panel = $(".glass-panel");
	footer = $("footer.footer");
}

$(document).ready(function() {	
	enableVerticalPositioning();
	makeTableDOMConnections();	
	prepareForVerticalPositioning();
	enableSortingCapabilities();
	
	$("#add-user-button").click(function(e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: 'includes/modules/superadmin-clients-add.php',
			success: function(msg) {
				setRightHandSide(msg);
			},
			error: function (request, status, error) { failNotification(); }
		});
	});
});

function setRightHandSide(msg) {
	leftHandSideIsCompressed = true;
	hideOrShowLeftHandSidesCollapsableItems();
	$("#add-user-button").hide();
	$("#right-hand-side").removeClass("fadeOutRightBig").addClass("animated fadeInRightBig").html(msg);
	setTimeout(function() {
	$("#left-hand-side").removeClass("col-md-12").addClass("col-md-4");
	}, 200);
}

function hideOrShowLeftHandSidesCollapsableItems() {
	if (leftHandSideIsCompressed === true) {
		$(".hide-on-collapsed-view").hide();
	} else {
		$(".hide-on-collapsed-view").show();
	}
}

leftHandSideIsCompressed = false;

function makeInfoDOMConnections() {
	makeInfoPanelCloseButtonConnection();
}