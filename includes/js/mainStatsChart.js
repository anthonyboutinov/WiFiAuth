$( document ).ready(function() {

	var legendTipIsToggled = false;
	var ledengTip = $("#legend-tip");
	
	function hideOrShowLegendTipDependingOnScreenSize() {
		if ($(window).width() < 992) {
			$(ledengTip).removeClass("zero-opacity").addClass("legend-tip-active");
		} else {
			$(ledengTip).addClass("zero-opacity").removeClass("legend-tip-active");
		}
	}
	
	hideOrShowLegendTipDependingOnScreenSize();
	$(window).resize(function() {
		hideOrShowLegendTipDependingOnScreenSize();
// 			$("graph-col").setMarginTop(($(window.top).height() - $("#graph-col").outerHeight() ) / 2);
	});
	
	$("#legend").mouseenter(function() {
		if ($(window).width() >= 992) {
			$(ledengTip).addClass("fadeInDown").removeClass("fadeOutUp").delay(600).queue(function(){
					$(this).addClass("legend-tip-active").dequeue();
			});
			legendTipIsToggled = true;
		}
	}).mouseleave(function() {
		legendTipIsToggled = false;
		if (legendTipIsToggled || $(window).width() >= 992) {
			$(ledengTip).removeClass("fadeInDown").addClass("fadeOutUp").removeClass("legend-tip-active");
		}
	});
	
	function setMarginTop() {
		return ($(window.top).height() - $("#graph-col").outerHeight() ) / 2; // center vertically
	}
	
	setTimeout(function() {
		$("#graph-col").scrollToFixed({
			zIndex: 400,
			marginTop: setMarginTop(), // TODO: СДЕЛАТЬ ОБЕРТКУ НАД ЭТИМ ДИВОМ, УКАЗАТЬ ЕГО CSS MIN-HEIGHT=WINDOW.HEIGHT И ЧЕРЕЗ JQUERY ВЫЧИСЛЯТЬ И КОРРЕКТИРОВАТЬ ОТСТУП ЭТОГО ВНУТРИ ТОГО
			minWidth: 992, // use it only on medium sized devices and above
			minHeight: $("#graph-col").outerHeight()
		});
	}, 900); // delay this action by 1 second (wait for #graph-col to get to its full height (graph is generated after page loads))
	
	
	$(window).resize(drawChart);
	
	
});