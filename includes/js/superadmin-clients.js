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
	
	function genRouterToken() {
		$("#router-token").val($.password(16,true));
	}
	$(generateTokenButton).click(genRouterToken);
	genRouterToken();
	
	function genPassword() {
		var password = $.password(4,false)+'-'+$.password(4,false)+'-'+$.password(4,false);
		$("#password").val(password.toUpperCase());
	}
	$(generatePasswordButton).click(genPassword);
	genPassword();


    $("[data-id='enabled']").click(function (e) {
        e.preventDefault();

        var idUser;

        $('#alertModal').modal('show');

        idUser = $(this).attr("data-idDBUser");
         // $.ajax({
         //        type: "POST",
         //        url: "superadmin-query.php",
         //        data:{ 
         //            'idUser': idUser, 
         //            'active':'F', 
         //            'form-name': 'enable-disable-user'
         //        },
         //        success: function(msg){
         //            setTimeout(function(){location.reload();}, 600);
         //        }
         //        }); 
    }).mouseenter(function() {
	    $(this).find("i").removeAttr('class').addClass('fa fa-times-circle');
    }).mouseleave(function() {
	    $(this).find("i").removeAttr('class').addClass('fa fa-circle');
    });
    
    $("[data-id='disabled']").click(function (e) {
        e.preventDefault();

        var idUser;

        idUser = $(this).attr("data-idDBUser");
         $.ajax({
                type: "POST",
                url: "superadmin-query.php",
                data:{ 
                    'idUser': idUser , 
                    'active':'T', 
                    'form-name': 'enable-disable-user'
                },
                success: function(msg){
                 setTimeout(function(){location.reload();}, 600);

                }
                }); 

    }).mouseenter(function() {
	    $(this).find("i").removeAttr('class').addClass('fa fa-dot-circle-o');
    }).mouseleave(function() {
	    $(this).find("i").removeAttr('class').addClass('fa fa-circle-o');
    });
	
});