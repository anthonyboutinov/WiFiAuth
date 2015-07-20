<?php
	require '../core/session.php';
	
	if (!isset($_GET['ID_VAR'])) {
		fatalError("DEBUG Error in includes/modules/history.php: ID_VAR not set");
	}
		
	if (!isset($_GET['presentation'])) {
		fatalError("DEBUG Error in includes/modules/history.php: presentation not set");
	}
	
	function drawTable($rows) {
		?><table class="table"><?php
	
		$i = 0;
		if ($rows && $rows->num_rows > 0) {
			while($row = $rows->fetch_assoc()) {
				?>
				<tr>
					<td class="text-right"><?=++$i;?></td>
					<td>
						<a href="#" data-revert-history="<?=$row['ID_VAR'];?>"><?=htmlentities($row['OLD_VALUE']);?><?php if (isset($row['OLD_BLOB_VALUE'])) { ?>
							<img src="data:image/jpeg;base64,<?=base64_encode($row['OLD_BLOB_VALUE']);?>" class="tiny-image-preview">
							<?php } ?></a>
					</td>
					<td class="text-right"><?=$row['OLD_DATE_CREATED'];?></td>
				</tr>
				<?php
			}
		} else {
			?>
			<td colspan="3">Пусто</td>
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
						if ($(_this).html()==$(_this).text()) {
							$("[data-history-receiver='<?=$_GET['ID_VAR'];?>']").val($(_this).html());
						} else {
							$("[data-history-src-receiver='<?=$_GET['ID_VAR'];?>']").attr('src', $(_this).html().find('img').attr('src'));
						}
					},
					fail: failNotification
				});
			});
		</script>
		<?php
	}
	
	$rows = $database->getHistory($_GET['ID_VAR']);
	
	$presentation = $_GET['presentation'];
	if ($presentation == 'modal') {
		Error::fatalError('DEBUG Error in includes/modules/history.php: modal presentation недоделан! Если нужен, то доработать.');
		?>
		
		<div class="modal fade" id="modalHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-black">
				<!-- <form> -->
					<div class="modal-content narrow-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body"><?php drawTable($rows); ?></div>
						<div class="modal-footer">
							<!-- Оставить modal-footer. Он просто пустой. -->
						</div>
					</div>
				<!-- </form> -->
			</div>
		</div>
		<script>
// 			makeHistoryWindowDOMConnections();
		</script>
		
		<?php
	} else {
		drawTable($rows);
	}
		
?>