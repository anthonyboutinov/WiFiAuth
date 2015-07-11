/**
 *	addNotification
 *
 *	Добавить уведомление с помощью jQuery.
 *	При передаче пустого или неопределенного параметра msg будет выводиться
 *	предупреждение.
 *	Сообщения типа success автоматически убираются с экрана чезе 10 секунд.
 *	
 *	@author Anthony Boutinov
 *	
 *	@param (msg) (string)		Сообщение
 *	@param (kind) (string)		Тип сообщения
 */
function addNotification(msg, kind) {
	
	if (msg == undefined || msg == '') {
		addNotification('DEBUG: (includes/js/Notification.js): Пустое сообщение', 'warning');
		return;
	}
	$("body").append('<div class="notification bg-'+kind+' animated bounceInDown no-padding"><a class="pull-right" href="#" onclick="$(this).parent().remove();"><i class="fa fa-times"></i><span class="sr-only">Закрыть уведомление</span></a>'+msg+'</div>');
	
	if (kind == 'success') {
		setTimeout(function() {
			$(".notification.bg-success").removeClass('bounceInDown').addClass('bounceOutUp').wait(1000).remove();
		}, 8000);
	}
	
}

/**
 *	addNotification
 *
 *	Добавить стандартизированное уведомление об ошибке выполнения запроса
 *	с помощью jQuery.
 *	
 *	@author Anthony Boutinov
 *	
 *	@param (logError) (bool)		(Опционально) Логировать ли в консоль сообщение. По умолчанию, undefined
 */
function failNotification(logError) {
	if (logError != undefined && logError != false) {
		console.log(logError);
	}
	addNotification('Ошибка при выполнении запроса. Попытайтесь еще раз.', 'danger');
}

$(document).ready(function(){
	
	// Найти уведомления типа success и удалить их через 10 секунд после загрузки страницы
	setTimeout(function() {
		$(".notification.bg-success").removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).remove();
		$(".sub-notification.bg-success").removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).remove();
	}, 8000);
	
	
});