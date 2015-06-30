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
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
  }
});


$(document).ready(function() {
	var generateTokenButton = $("#generate-token");
	var generatePasswordButton = $("#generate-password");
	
	function genRouterToken() {
		$("#router-token").val($.password(16,true));
	}
	$(generateTokenButton).click(genRouterToken);
	genRouterToken();
	
	function genPassword() {
		$("#password").val($.password(20,false));
	}
	$(generatePasswordButton).click(genPassword);
	genPassword();

    function addNewDBUser() {

    var companyName;
    var email;
    var routerToken;
    var login;
    var password;
    var routerLogin;

    company = $("#company-name").val();
    email = $("#email").val();
    routerToken = $("#router-token").val();
    login = $("#login").val();
    password = $("#password").val();
    routerLogin = $("#router-login").

    $.ajax({
            type: "POST",
            url: "superadmin-query.php",
            data: "company=" + company + "&email=" +email+"&router="+routerToken+"&login="+login+"&password="+password+"&roterlogin="+routerLogin,
            success: function(msg){
              alert("Пользователь добавлен!");
            }
            });
    }

    $("#addUser").click(addNewDBUser);
	
});