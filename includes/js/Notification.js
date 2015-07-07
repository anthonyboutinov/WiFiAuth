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

function failNotification(logError) {
	if (logError != undefined) {
		console.log(logError);
	}
	addNotification('Ошибка при выполнении запроса. Попытайтесь еще раз.', 'danger');
}

$(document).ready(function(){
	
	setTimeout(function() {
		$(".notification.bg-success").removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).remove();
		$(".sub-notification.bg-success").removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).remove();
	}, 8000);
	
	
});