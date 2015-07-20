<?php
	include 'includes/base/admin.php';
	$protector->protectPageForbidSuperadmin();
	
	$dictionary_branches = ['POST', 'GENERAL_FIELDS', 'ADMIN_DISPLAY_SETTINGS', 'LOGIN_OPTIONS', 'PASSWORD'];

	$processSettingsUpdateResponce = null;
	$additionalScripts = "";
		
	if (isset($_POST['form-name'])) {
		if ($_POST['form-name'] == 'admin-settings') {
			$processSettingsUpdateResponce = $database->processPostRequestUpdateVars($dictionary_branches);
		} else if ($_POST['form-name'] == 'admin-password') {
			echo $database->updateDBUserPassowrd();
			exit();
		}
	}
	
	$settings = $database->getValuesForParentByShortName($dictionary_branches);
	
?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Настройки <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page popover-table-container"><div class="background-cover"></div>
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>
			
			<h1 class="huge-cover"><i class="fa fa-cogs"></i> Настройки</h1>

			<div class="row">
				<div class="col-md-3 col-md-push-9 hidden-sm hidden-xs">
					<ul class="list-unstyled" role="complementary" data-spy="affix" data-offset-top="208" data-offset-bottom="200" id="affix-menu">
						<?php
							$prevFieldParent = null;
							$isFirst = true;
							foreach ($settings as $key => $value) {
								if ($value['ID_PARENT'] != $prevFieldParent) {
									$prevFieldParent = $value['ID_PARENT'];
									
								?><li><a href="#setting-group-<?=$value['ID_PARENT'];?>"><?=$value['PARENT_NAME'];?></a></li><?php
								
								}
								
							}
						?>
						<li><a href="#settings-password-change">Сменить пароль</a></li>
					</ul>
				</div>
				<div class="col-md-9 col-md-pull-3" role="main">

					<div class="page-wrapper text-center">
						
						<form method="post" enctype="multipart/form-data" action="admin-settings.php" id="admin-settings-form">
							<input type="hidden" name="form-name" value="admin-settings">
										
							<?php
								$prevFieldParent = null;
								$isFirst = true;
								foreach ($settings as $key => $value) {
									if ($value['ID_PARENT'] != $prevFieldParent) {
									
									if ($prevFieldParent != null) { ?>
										<div class="action-buttons-mid-way-panel">
											<button type="submit" class="btn btn btn-black gradient">Сохранить <i class="fa fa-floppy-o"></i></button>
											<?php if ($processSettingsUpdateResponce === true || $processSettingsUpdateResponce === false) { 
												echo '<i class="text-'.($processSettingsUpdateResponce === true ? 'success' : 'danger').' fa fa-'.($processSettingsUpdateResponce === true ? 'check-circle' : 'times-circle').'"></i>'; 
											} ?>
										</div>
									</div>
										<?php
									}
									
									if ($value['ID_PARENT'] != $prevFieldParent) {
										$prevFieldParent = $value['ID_PARENT'];
									}
									?>
									
									<a name="setting-group-<?=$value['ID_PARENT'];?>">
										<h2 class="<?php
										if ($isFirst == true) {
											$isFirst = false;
										} else {
											echo 'divide-top';
										}
										?>"><?=$value['PARENT_NAME'];?></h2>
									</a>
									<div class="form-horizontal">
									<? } ?>
									
										<div class="form-group">
											<label class="col-sm-4 control-label<?php if ($value['DATA_TYPE'] == 'checkbox') { echo ' col-xs-9'; } ?>" for="<?=$key;?>"><?=$value['NAME'];?></label>
											<div class="col-sm-7<?php if ($value['DATA_TYPE'] == 'checkbox') { echo ' col-xs-2 col-contains-checkbox'; } ?>">
												<?php
													
													
												if ($value['DATA_TYPE'] == 'text&file' || $value['DATA_TYPE'] == 'file') { // ЕСЛИ ФАЙЛ
													$addFileScript = true;
													
													if (isset($value['BLOB_VALUE'])) { ?>
													<small>Изображение, которое используется сейчас:</small>
													<img src="data:image/jpeg;base64,<?=base64_encode($value['BLOB_VALUE']);?>" class="tiny-image-preview" data-history-src-receiver="<?=$value['ID_VAR'];?>">
													<?php } ?>
													<div class="input-group">
										                <span class="input-group-btn">
															<span class="btn btn-black btn-file">
																Выбрать&hellip; <i class="fa fa-folder-open-o"></i>
																<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
																<input
																	type="file"
																	accept="image/png, image/jpeg, image/gif"
																	class="form-control"
																	name="<?=$key;?>_file"
																	id="<?=$key;?>_file"
																	value="<?=$value['VALUE'];?>">
															</span>
										                </span>
														<input type="text" class="form-control" readonly data-type="file-name">
													</div>
													<?php
												}
												if ($value['DATA_TYPE'] == 'textarea') { // ЕСЛИ TEXTAREA ?>
												
													<textarea rows="4"
														class="form-control"
														data-history-receiver="<?=$value['ID_VAR'];?>"
														name="<?=$key;?>"
														id="<?=$key;?>"><?=$value['VALUE'];?></textarea>
													<div class="textarea-word-count" id="<?=$key;?>_word_count">≤200</div>
													
													<?php ob_start(); ?>
														var textarea_<?=$key;?> = $("#<?=$key;?>");
														var word_count_<?=$key;?> = $("#<?=$key;?>_word_count");
														$(textarea_<?=$key;?>).keyup( function() {
																update_textarea_word_count(
																	$(textarea_<?=$key;?>),
																	$(word_count_<?=$key;?>)
																);
															}
														);
														update_textarea_word_count(textarea_<?=$key;?>, word_count_<?=$key;?>);
													<?php $additionalScripts = $additionalScripts.ob_get_clean(); 
														
														
														
												} else if ($value['DATA_TYPE'] != 'file') { // ЕСЛИ СТАНДАРТНОЕ
													
													if ($value['DATA_TYPE'] == 'checkbox') { // ЕСЛИ CHECKBOX
														echo '<div class="checkbox">';
													}
												?>
													<input
														type="<?=$value['DATA_TYPE'];?>"
														class="form-control"
														id="<?=$key;?>"
														name="<?=$key;?>"
														data-history-receiver="<?=$value['ID_VAR'];?>"
														<?=( // ЕСЛИ CHECKBOX
															$value['DATA_TYPE'] == 'checkbox' ? (
																$value['VALUE'] == 'T' ? 'checked' : ''
															)
															: ('value="'.$value['VALUE'].'"')
														);?>><?php
												
														
													if ($value['DATA_TYPE'] == 'checkbox') { // ЕСЛИ CHECKBOX
														echo '<label></label></div>';
													}
													
												}
													
												if ($value['COMMENT']) { ?>
													<small><?=$value['COMMENT'];?></small>
												<?php } ?>
											</div>
											<div class="col-sm-1 col-xs-1">
												<?php if ($value['HISTORY_COUNT'] > 0) { ?>
												<label class="control-label">
													<a href="#" title="Показать историю" role="button" tabindex="0" data-id-var="<?=$value['ID_VAR'];?>"><i class="fa fa-history"></i></a>
												</label>
												<?php } ?>
											</div>
										</div>
									
									<?php
								}
							?>
							
								<div class="action-buttons-mid-way-panel">
									<button type="submit" class="btn btn btn-black gradient">Сохранить <i class="fa fa-floppy-o"></i></button>
									<?php if ($processSettingsUpdateResponce === true || $processSettingsUpdateResponce === false) { 
										echo '<i class="text-'.($processSettingsUpdateResponce === true ? 'success' : 'danger').' fa fa-'.($processSettingsUpdateResponce === true ? 'check-circle' : 'times-circle').'"></i>'; 
									} ?>
								</div>
							</div>
						
						</form>
						
						<a name="settings-password-change"><h2 class="divide-top">Сменить пароль</h2></a>
						<div class="form-horizontal">
							<form method="post">
								<input type="hidden" name="form-name" value="admin-password">
						
								<div class="form-group">
									<label class="col-sm-4 control-label" for="old-password">Старый пароль</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="old-password" id="old-password">
									</div>
								</div>
						
								<div class="form-group">
									<label class="col-sm-4 control-label" for="password">Новый пароль</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="password" id="password">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-4 control-label" for="repeat-password">Повторите пароль</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="repeat-password" id="repeat-password">
									</div>
								</div>
						
								<div class="action-buttons-mid-way-panel last-child">
									<button type="button" class="btn btn btn-black gradient" id="password-submit">Сохранить <i class="fa fa-floppy-o"></i></button>
								</div>
						
							</form>
						</div>
						
					</div>
			
				</div><!-- eof col -->
			</div><!-- eof .row -->
			<?php	include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script type="text/javascript" src="includes/js/jquery.numeric.min.js"></script>
		<script type="text/javascript" src="includes/js/jquery.alphanum.js"></script>
		<script type="text/javascript" src="includes/js/admin-settings.js"></script>
		<script>$(document).ready(function() {<?=$additionalScripts;?>});</script>
		<?php if (isset($addFileScript) && $addFileScript) { ?>
			<script>// Добавление названия справа от кнопки Добавить... в областях выбора файлов при выборе файла для загрузки
				
				$(document).on('change', '.btn-file :file', function() {
				  var input = $(this),
				      numFiles = input.get(0).files ? input.get(0).files.length : 1,
				      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
				  input.trigger('fileselect', [numFiles, label]);
				});
				
				$(document).ready(function() {
				    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
				        
				        var input = $(this).parents('.input-group').find(':text'),
				            log = numFiles > 1 ? numFiles + ' files selected' : label;
				        
				        if( input.length ) {
				            input.val(log);
				        } else if(log) {
							alert(log);
				        }
				        
				    });
				});
			</script>
		<?php } ?>
	</body>
</html>