<?php
	Notification::add("birthdaysScript.php");
if (!isset($_GET["mobile"])) {
// ============= DESKTOP scripts =============
?>
<script>
function resizeScrollableTables() {

	// Задается ширина колонок в строке заголовка равной ширине колонок основной части в таблице Постоянные клиенты, состоящей из двух отдельностоящих таблиц.
	$("#table-head-part-col-1").width($("#table-scrollable-part-col-1").width());
	$("#table-head-part-col-2").width($("#table-scrollable-part-col-2").width());
	$("#table-head-part-col-3").width($("#table-scrollable-part-col-3").width());

	// Задать высоту пространства видимой части таблиц со скроллингом
	var pageWrapper = $(".page-wrapper");
	var deltaHeight = $(".navbar").outerHeight(true) + $("h1").outerHeight(true) + $("footer.footer").outerHeight(true) + $(".table-head-row").outerHeight(true) + parseInt(pageWrapper.css("padding-top")) + parseInt(pageWrapper.css("padding-bottom")) + parseInt(pageWrapper.css("margin-bottom"));
	$(".scrollable").height($(window).height() - deltaHeight);
}

$( document ).ready(function() {
	// Задать высоты таблиц после загрузки страницы
	resizeScrollableTables();

});

// Пересчитывать высоты таблиц при изменении размеров окна браузера
$( window ).resize(resizeScrollableTables);
</script>
<?php
// ============= eof DESKTOP scripts =============
} ?>