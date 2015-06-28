var birthdayScrollable = $("#birthdays-scrollable");

function visibleHeight() {
	var pageWrapper = $(".page-wrapper");
	var deltaHeight = $(".navbar").outerHeight(true) + $("h1").outerHeight(true) + $("footer.footer").outerHeight(true) + $(".table-head-row").outerHeight(true) + parseInt(pageWrapper.css("padding-top")) + parseInt(pageWrapper.css("padding-bottom")) + parseInt(pageWrapper.css("margin-bottom"));
	return $(window).height() - deltaHeight;
}

var visibleHeight = visibleHeight();

function scrollMaskingSub(_scrollable) {
	
	var _scrollHeight = $(_scrollable)[0].scrollHeight;
	if (visibleHeight < _scrollHeight) {
		
		var scrollTopOffset = $(_scrollable).scrollTop();
		if (scrollTopOffset == 0) {
			$(_scrollable).addClass("scrollable-has-overflow-below");
			$(_scrollable).removeClass("scrollable-has-overflow");
		} else if (scrollTopOffset >=  _scrollHeight - $(_scrollable).outerHeight()) {
			$(_scrollable).addClass("scrollable-has-overflow-above");
			$(_scrollable).removeClass("scrollable-has-overflow");
		} else {
			$(_scrollable).addClass("scrollable-has-overflow");
			$(_scrollable).removeClass("scrollable-has-overflow-above");
			$(_scrollable).removeClass("scrollable-has-overflow-below");
		}
		
	} else {
		$(_scrollable).removeClass("scrollable-has-overflow");	
	}
	
}

function scrollMasking() {
	scrollMaskingSub(birthdayScrollable);
}
		
function resizeScrollableTables() {
	// Задается ширина колонок в строке заголовка равной ширине колонок основной части в таблице Клиенты, состоящей из двух отдельностоящих таблиц.
	
	var headers = $("#birthdays-header > tbody > tr > td")
	var bodies = $("#birthdays-scrollable > table > tbody > tr:first-child > td");

	for (i = 0; i < headers.length; i++) {
		$(headers[i]).width($(bodies[i]).width());
	}
	
	// Задать высоту пространства видимой части таблиц со скроллингом
	$(".scrollable").height(visibleHeight);
	
	scrollMasking();
}


$(document).ready(resizeScrollableTables);
$(window).resize(resizeScrollableTables);
$(birthdayScrollable).scroll(scrollMasking);