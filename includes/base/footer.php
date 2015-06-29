<footer class="footer">
<!-- 	Разработано<span class="sr-only"> Авторское право</span> &copy; 2015 <a href="#" target="_blank">ПочтиГотово</a> -->
</footer>

<?php
// 	Notification::add("Total number of queries performed: ".$database->getNumQueriesPerformed());
	if (count(Notification::getMessages()) > 0) {
	$first_key = key(Notification::getMessages());
?>
<div class="notification group-notification bg-<?=$first_key;?> animated bounceInDown no-padding"><?php
	
	foreach (Notification::getMessages() as $kind => $message) {
		
		?>
		<div class="sub-notification bg-<?=$kind;?>">
			<a class="pull-right" href="#" onclick="$(this).parent().remove();"><i class="fa fa-times"></i><span class="sr-only">Закрыть уведомление</span></a>
			<?=$message;?>
		</div>
		
		
		<?
		
	}
?></div><?php } ?>