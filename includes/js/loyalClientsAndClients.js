var loyalScrollable = $("#loyal-clients-scrollable");
var clientsScrollable = $("#clients-scrollable");

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
	scrollMaskingSub(loyalScrollable);
	scrollMaskingSub(clientsScrollable);	
}
		
function resizeScrollableTables() {
	// Задается ширина колонок в строке заголовка равной ширине колонок основной части в таблице Клиенты, состоящей из двух отдельностоящих таблиц.
	$("#clients-table-head-part-col-1").width($("#clients-table-scrollable-part-col-1").width());
	$("#clients-table-head-part-col-2").width($("#clients-table-scrollable-part-col-2").width());
	$("#clients-table-head-part-col-3").width($("#clients-table-scrollable-part-col-3").width());
	$("#clients-table-head-part-col-4").width($("#clients-table-scrollable-part-col-4").width());
	
	// Задается ширина колонок в строке заголовка равной ширине колонок основной части в таблице Постоянные клиенты, состоящей из двух отдельностоящих таблиц.
	$("#loyal-clients-table-head-part-col-1").width($("#loyal-clients-table-scrollable-part-col-1").width());
	$("#loyal-clients-table-head-part-col-2").width($("#loyal-clients-table-scrollable-part-col-2").width());
	$("#loyal-clients-table-head-part-col-3").width($("#loyal-clients-table-scrollable-part-col-3").width());
	
	// Задать высоту пространства видимой части таблиц со скроллингом
	$(".scrollable").height(visibleHeight);
	
	scrollMasking();
}


$(document).ready(resizeScrollableTables);
$(window).resize(resizeScrollableTables);
$(loyalScrollable).scroll(scrollMasking);
$(clientsScrollable).scroll(scrollMasking);