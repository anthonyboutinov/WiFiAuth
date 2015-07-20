<?php
	require '../core/session.php';
	
	if (!isset($_GET['ID_VAR'])) {
		fatalError("DEBUG Error in includes/modules/history.php: ID_VAR not set");
	}
		
	if (!isset($_GET['presentation'])) {
		fatalError("DEBUG Error in includes/modules/history.php: presentation not set");
	}
	
	$rows = $database->getHistory($_GET['ID_VAR']);
	
	$presentation = $_GET['presentation'];
	if ($presentation == 'modal') {
		
		Error::fatalError('DEBUG Error in includes/modules/history.php: modal presentation неопределен! Если нужен, то сделать.');
		
	} else {

# ============================== #
//!DEFAULT PRESENTATION
# ============================== #

		?><table class="table"><?php
	
		$i = 0;
		if ($rows && $rows->num_rows > 0) {
			while($row = $rows->fetch_assoc()) {
				?>
				<tr>
					<?php if (isset($row['OLD_BLOB_VALUE'])) { ?>
						<td class="text-center position-relative">
							<a href="#" data-revert-history="<?=$row['ID_VAR'];?>">
								<img src="data:image/jpeg;base64,<?=base64_encode($row['OLD_BLOB_VALUE']);?>" class="tiny-image-preview" style="max-width: 100%;">
							</a>
							<span class="history-general-row-timestamp"><?=$row['OLD_DATE_CREATED'];?></span>
						</td>
					<?php } else { ?>
						<td>
							<a href="#" data-revert-history="<?=$row['ID_VAR'];?>"><?=htmlentities($row['OLD_VALUE']);?></a>
						</td>
						<td class="text-right" style="min-width: 135px;"><?=$row['OLD_DATE_CREATED'];?></td>
					<?php } ?>
				</tr>
				<?php
			}
		} else {
			?>
			<td class="text-center">Пусто</td>
			<?php
		}
		?></table>
		<script>
			$("[data-revert-history]").click(function(e) {
				var _this = $(this);
				e.preventDefault();
				$.ajax({
					type: "GET",
					url: "admin-query.php",
					data: {
						'form-name': 'revert-history',
						'ID_VAR': $(this).attr('data-revert-history')
					},
					success: function(msg) {
						
						setTimeout(function() {
							$("[data-id-var='<?=$_GET['ID_VAR'];?>']").popover('hide');
						}, 500);
						
						if ($(_this).html()==$(_this).text()) {
							$("[data-history-receiver='<?=$_GET['ID_VAR'];?>']").val($(_this).html());
							addNotification('Изменение сохранено: установлено значение "'+$(_this).html()+'".', 'success');
						} else {
							$("[data-history-src-receiver='<?=$_GET['ID_VAR'];?>']").attr('src', $(_this).find('img').attr('src'));
							addNotification('Изменение сохранено: задан новый файл.', 'success');
						}
						
					},
					error: function (request, status, error) { failNotification(); }
				});
			});
		</script>
		<?php
			
	} // EOF DEFAULT PRESENTATION
		
?>