$(document).ready(function () {

	// включить подсказки
	$('[data-toggle="tooltip"]').tooltip({'html': true});
	
	// обработать нажатие на переключатель
	$("#intellectual-view-toggle").click(function(e){
		e.preventDefault(); 
		
		// чем заменить содержимое
		$(this).html('<i class=\'fa fa-spinner fa-pulse\'></i> Умная сортировка');
		//получить значение из куки
		var ccv = Cookies.get('birthdays-intellectual-view'); // (1)
		// санитизировать значение
		if (ccv == 1) {ccv = 0;} else {ccv = 1;}
		
		// если через 3 сек не произойдет ничего, значит (1) не работает, что означает, что исп. старый браузер.
		// В таком случае сделать следующее:
		setTimeout(function() {
			
			// Убрать подсказку "?"
			$("#option-help").remove();
			
			// чем заменить
			var replacement = '<span id="ntlt" data-toggle="tooltip" data-placement="bottom" title="Скорее всего Вы используете устаревший браузер" style="cursor:help;"><i class=\'fa fa-frown-o\'></i> Ошибка</span>';
			$("#intellectual-view-toggle").html(replacement);
			
			// replace element type to '<span />'
			var attrs = { };
			$.each($("#intellectual-view-toggle")[0].attributes, function(idx, attr) {
			    attrs[attr.nodeName] = attr.nodeValue;
			});
			$("#intellectual-view-toggle").replaceWith(function () {
			    return $("<span />", attrs).append($(this).contents());
			});
			
			// активировать подсказку к новому объекту
			$("#ntlt").tooltip();
			
		}, 3000);
		
		// задать обратное значение в куки
		Cookies.set('birthdays-intellectual-view', ccv);
		// подождать пока выполнится предыдущая строчка кода и перезагрузить страницу
		setTimeout(function() {location.reload();}, 500); // из-за iPhone увеличино с 400 до 500
	});
	
	
	// переворот карточки
	$('.flip-birthdays-card').click(function(e) {
		e.preventDefault(); 
		$('#birthdays-card').toggleClass("flipped");
	});
	
	// коррекция высот (задержка нужна, потому что иначе высота неправильно высчитывается)
	function correctHeight() {
		var fheight = 0;
		$("#birthdays-card > figure.front").children().each(function() {
			fheight += $(this).outerHeight() + parseInt($(this).css('margin-bottom'));
		})
		$("#birthdays-card > figure > .back").css('height', fheight);
		$("#birthdays-card > figure.back .page-wrapper").css('height', $("#birthdays-card > figure.front .page-wrapper").outerHeight());
		$("#birthdays-card").parent().css('height', fheight);
	}
	
	setTimeout(correctHeight, 350);
	$(window).resize(correctHeight);
	
});