<?php
	
	$post = $database->getValuesForParentByShortName('POST');
	
	// Заголовок поста
	$postTitle = $post['POST_TITLE']['VALUE'];
	
	// Содержание текста для постов
	$postContent = $post['POST_TEXT']['VALUE'];
	
	//Ссылки на изображения для постов
	$photoFB = $post['POST_IMAGE_FB']['VALUE'];
	
	//Ссылки на страницы клиентов
	$linkFB = $post['POST_LINK_FB']['VALUE'];
	
?><script>
$(document).ready(function(){

	$("input[type=\"phone\"]").numeric({ decimal: false, negative: false }, function() {this.value = "1"; this.focus(); });


/* *
   * Вертикальное позиционирование
 */


	// Поиск ключевых элементов в DOM
	
		var panel = $(".glass-panel");
		var footer = $("footer.footer");
		var loginInputPasswordForm = $("#modalPassword > .modal-dialog");
		var loginInputPasswordFormButton = $("#loginInputPasswordFormButton");
			
	// EOF Поиск ключевых элементов в DOM
	
	// Функции
	
		function positionVertically() {
			if ($(panel).outerHeight() < $(window).height()) {
				$(panel).css('margin-top', ($(window).height() - $(panel).outerHeight()) / 2);
				$(footer).css('position', 'fixed');
			} else {
				$(panel).css('margin-top', 0);
				$(footer).css('position', 'inherit');
			}	
			$(loginInputPasswordForm).css('margin-top', ($(window).height() - $(loginInputPasswordForm).outerHeight()) / 2);
		}
						
		function openLoginInputPasswordForm() {
			setTimeout(positionVertically, 200);
			$("#phone-form").focus();
		}
	
	// EOF Функции
	
	// Привязки к дейсвтиям

		$(loginInputPasswordFormButton).click(openLoginInputPasswordForm);
		positionVertically();
		setTimeout(positionVertically, 200);
		$(window).resize(positionVertically);
	
	// EOF Привязки к дейсвтиям
	
// EOF Вертикальное позиционирование
	
/* *
   * Работа с соцсетями
 */


	var password;
	
	var userId;
	var href;
	var fname;
	var lname;
	var birthday;
	var photos;
	
	VK.init({apiId:4933055});    

	function authInfo(response) {      //функция проверки авторизации пользователя Вконтакте
		if (!response.session) {
			alert('Необходимо войти с помощью ВКонтакте и разрешить доступ!');
			return false;
			
		} else{  

			console.log(response);
			
			fname = response.session.user.first_name;
			lname = response.session.user.last_name;
			href = response.session.user.href;
			userId = response.session.user.id;
		
			VK.Api.call('users.get',{ fields:'bdate, photo_50'}, function(resp){    
	
		            
				birthday = resp.response[0].bdate;
				photos = resp.response[0].photos_50;
				
				$.ajax({
					type: "POST",
					url: "query.php",
					data: {
						'fname': fname, 
						'lname':lname,
						'ref':href,
						'logOpt':'vk',
						'bdate':birthday,
						'photos':photos,
						'form-name':'addUser'
					},
					success: function(msg){

						location.href="wifihotspot.php";
					}
				});
			});
		}
	}
	
	function vkPosting(){  // функция для постинга Вконтакте

		var photoVK = '<?php echo $photoVK; ?>';
		var linkVK = '<?php echo $linkVK; ?>';
		var photo = photoVK+ "," +linkVK;
		
		VK.Api.call(
			'wall.post',
			{
				message: '<?php echo $postContent; ?>',
				attachments: photo
			},
			function(r) {   
				if (r.error) { //проверка на ошибки ответа от сервера
					console.log(r.error);
					addNotification('Для авторизации необходимо разместить запись на стене.','danger');
					
					if (r.error.error_code == 10007) {
					} else {
						addNotification('Произошла неизвестная ошибка, повторите позже.', 'danger');
					}
					return false;
				}
				//если ошибок нет то размещается пост и пользователя перебрасывает на другую страницу
		
				addNotification('Пост успешно опубликован!','success');
				location="<?php echo $routerAdmin; ?>";
				
			} // eof function(r)
			
		); // eof VK.Api.call
   }
   
	function vkLoginInput() {  //функция авторизации
	    VK.Auth.login(authInfo,8193);
	}
	
	function quo(min,max){
		return Math.floor(Math.random()*(max-min+1))+min; 
	}
	
	$("#phone-form").keyup(function() {
		var phonenum = $(this).val();
		// Отформатировать неверный формат входных данных: удалить 7, 8, +7 (невозможно ввести благодаря numeric штуке)
		if (phonenum[0] == '7' || phonenum[0] == '8') {
			$(this).val(phonenum.substr(1));
		}
	});
	
	$("#phoneButton").click(function(e) {  //функция входа по паролю
		e.preventDefault();

		var phonenum_input = $('#phone-form');
		var phonenum = $(phonenum_input).val();
		phone = '7'+phonenum;


		
		// обработка неверного значения номера телефона
		if(!phonenum) {
			addNotification('Введине номер телефона!','warning');
			return;
		} else if(phonenum.length != 10) { // если количество цифр неверно
			addNotification('Номер телефона задан неверно!','warning');
			return;
		}
		
		// Отформатировать неверный формат входных данных: удалить 7, 8, +7 (невозможно ввести благодаря numeric штуке)
		if (phonenum[0] == '7' || phonenum[0] == '8') {
			phonenum = phonenum.substr(1);
			$(phonenum_input).val(phonenum);


		}
		
		// Сгенерировать digital пароль
		password = "";
		for (var i = 0; i <4; i++) { // 4 цифры пароля
			password += quo(0,9);
		}

		$.ajax({
				type: "POST",
				url: "query.php",
				data: {
					'phone': phonenum,
					'text':password
				},
			success: function(msg){
				
				if (msg.lastIndexOf('100',0) === 0) {

					addNotification('Смс с кодом отправлено на ваш телефон','success');

				 	$.ajax({
						type: "POST",
						url: "query.php",
						data: {'phone': phone, 
							   'logOpt':'mobile',
							   'form-name':'addMobileUser'
							}

						});


				} else {
				 addNotification("Смс с кодом не отправлено, попробуйте еще раз!", 'danger');
				}
			$("#phone-pass-group").removeClass("hidden");
			},
			fail: failNotification
		});

 		
 		var phoneButton = $("#phoneButton");
 		var phoneButtonOldHTML = $(phoneButton).html();
		$(phoneButton).text("30").attr("disabled", 'disabled');
        var phoneTimer = window.setInterval(function() {
	        var timeCounter = $(phoneButton).html();
	        var updateTime = eval(timeCounter)- eval(1);
            $(phoneButton).html(updateTime);

            if(updateTime <= 0){
                clearInterval(phoneTimer);
                $(phoneButton).removeAttr('disabled').html(phoneButtonOldHTML);
            }
        }, 1000);
	
	});


	$("#password").keyup(function() {
		var pass_val = $(this).val();
		if (pass_val.length == 4) {
			if (pass_val == password){
				location="<?php echo $routerAdmin; ?>";
			} else {
				addNotification("Введенный пароль неверен!", 'danger');
			}
		}
	});	

	function FacebookLoginInput(){  //функция авторизации в Facebook

		FB.init({
			appId      : '941045885918244',
			xfbml      : true,
			version    : 'v2.3'
		});
		
		var userId;
		FB.login(
			function(response) {
				if (response.status=='connected') {
					postToFacebook(response);
				} else {
					addNotification('Для получения доступа к сети необходимо авторизоваться!', 'warning');
				}
			},
			{scope: 'publish_actions, user_photos, user_posts, user_relationships, user_birthday '}
		);
	} 

  function postToFacebook(response) {  //функция постинга в Facebook  
        var params = {};
        params['name'] = '<?php echo $postTitle; ?>'; 
        params['link'] = '<?php  echo $linkFB; ?>'; 
        params['description'] = '<?php echo $postContent; ?>';
        params['picture'] = '<?php echo $photoFB; ?>'; // размер только 484*252
        
        FB.api('/me/feed', 'post', params, function(response) {
	        
			// Если ошибка
			if (!response || response.error) {
	            failNotification(response.error);

			} else /* Если пост размещен успешно */ {
	            FB.api('/me',function (resp) {

		            // Если ошибка
		            if (!resp || resp.error) {
			            failNotification(response.error);
                    } else /* Если успешно */ {
						fname = resp.first_name;
						lname = resp.last_name;
						href = resp.link;
						birthday = resp.birthday;
						$.ajax({
							type: "POST",
							url: "query.php",
							data: {
								'fname': fname, 
								'lname':lname,
								'ref':href,
								'logOpt':'facebook',
								'bdate':birthday,
								'form-name':'addUser'
							},
							success: function(msg){
								$('#ModalFacebook').modal('hide');
								addNotification('Пост успешно опубликован!', 'success');
								location="<?php echo $routerAdmin; ?>";
							},
							fail: failNotification
						});
					} // eof elsif
	            }); // eof FB.api /me

          } // eof elsif
       }); // eof FB.api /me/feed
    }


   function newVKPosting(){

   		var y = document.body.clientHeight; 
		var x = document.body.clientWidth; 
		var url = 'https://oauth.vk.com/authorize?'+
					'client_id=4933055'+
					'&scope=73729'+
					'&redirect_uri=https://kazanwifi.ru/query.php'+
					'&response_type=code'+
					'&v=5.34';
		var params = 'menubar=no,location=no,resizable=yes,scrollbars=yes,status=no';
		var newWin = window.open(url,'vk',params);
		newWin.resizeTo(700,400);
		newWin.moveTo(((x-720)/2),((y-390)/2));
		newWin.focus();
		newWin.blur();
		VK.Api.call('wall.get',{
						count:1,
						filter:'owner'
					}, function (r){

						if(r.response[1].attachment.link.url=='<?php echo linkVK; ?>'){
							
							location="<?php echo $routerAdmin; ?>";
						
						} else {
							addNotification('Для выхода в интернет необходимо опубликовать пост!','warning');
						}
						}
					);
	}

	$("#VKLoginButton").click(vkLoginInput); //   newVKPosting
	$("#FBPostButton").click(FacebookLoginInput);
	$("#internetLogin").click(vkPosting);
});
</script>