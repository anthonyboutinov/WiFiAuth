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
	
		$is_first = true;
		$i = 0;
		if ($rows && $rows->num_rows > 0) {
			while($row = $rows->fetch_assoc()) {
				
				if ($is_first == true) {
					$is_first = false;
					if (isset($row['OLD_BLOB_VALUE'])) {
						echo '<div class="row">';
					} else {
						echo '<table class="table">';
					}
				}
				
				if (isset($row['OLD_BLOB_VALUE'])) { ?>
					<div class="text-center history-col
						col-md-<?=($rows->num_rows < 6 ? '12' : '4');?>
						col-sm-<?=($rows->num_rows < 4 ? '12' : '6');?>">
						<div class="history-image-and-timestamp-contaner">
							<a href="#" data-revert-history="<?=$row['ID_VAR'];?>">
								<img src="data:image/jpeg;base64,<?=base64_encode($row['OLD_BLOB_VALUE']);?>" class="tiny-image-preview">
							</a>
							<span><?=$row['OLD_DATE_CREATED'];?></span>
						</div>
					</div>
				<?php
					
				} else {
					
				?>
					<tr>
						<td>
							<a href="#" data-revert-history="<?=$row['ID_VAR'];?>"><?=htmlentities($row['OLD_VALUE']);?></a>
						</td>
						<td class="text-right"><?=$row['OLD_DATE_CREATED'];?></td>
					</tr>
				<?php }
					
			}
		} else {
			?><div class="text-center">Пусто</div><?php
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
							$("[data-id-var='<?=$_GET['ID_VAR'];?>']").popover('destroy');
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