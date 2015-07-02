function addNotification(msg, kind) {
	
	if (msg == undefined || msg == '') {
		alert('DEBUG: includes/js/Notification.js: Пустое сообщение');
		return;
	}
	$("body").append('<div class="notification bg-'+kind+' animated bounceInDown no-padding"><a class="pull-right" href="#" onclick="$(this).parent().remove();"><i class="fa fa-times"></i><span class="sr-only">Закрыть уведомление</span></a>'+msg+'</div>');
	
}
