<?php
	include 'includes/core/vars.php';
	$protector->protectPageAdminPage();
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
	
?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Настройки <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page">
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>

			<h1><i class="fa fa-cog"></i> Настройки</h1>
			<div class="page-wrapper text-center">
				
				<form method="post" enctype="multipart/form-data" action="admin-settings.php" id="admin-settings-form">
					<input type="hidden" name="form-name" value="admin-settings">
								
					<?php
						$prevFieldParent = null;
						$isFirst = true;
						foreach ($database->getValuesForParentByShortName($dictionary_branches) as $key => $value) {
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
							
							<h2 class="<?php
								if ($isFirst == true) {
									$isFirst = false;
								} else {
									echo 'divide-top';
								}
								?>"><?=$value['PARENT_NAME'];?></h2>
							<div class="form-horizontal">
							<? } ?>
							
								<div class="form-group">
									<label class="col-sm-4 control-label" for="<?=$key;?>"><?=$value['NAME'];?></label>
									<div class="col-sm-8">
										<?php
											
											
										if ($value['DATA_TYPE'] == 'text&file' || $value['DATA_TYPE'] == 'file') { // ЕСЛИ ФАЙЛ
											$addFileScript = true;
											
											if (isset($value['BLOB_VALUE'])) { ?>
											<small>Изображение, которое используется сейчас:</small>
											<img src="data:image/jpeg;base64,<?=base64_encode($value['BLOB_VALUE']);?>" class="tiny-image-preview">
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
												<input type="text" class="form-control" readonly>
											</div>
											<?php
										}
										if ($value['DATA_TYPE'] == 'textarea') { // ЕСЛИ TEXTAREA ?>
										
											<textarea rows="4"
												class="form-control"
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
				
				<h2 class="divide-top">Сменить пароль</h2>
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
							<?php if ($updateDBUserPasswordResponce === true || $updateDBUserPasswordResponce === false) { 
								echo '<i class="text-'.($updateDBUserPasswordResponce == true ? 'success' : 'danger').' fa fa-'.($updateDBUserPasswordResponce == true ? 'check-circle' : 'times-circle').'"></i>'; 
							} ?>
						</div>
				
					</form>
				</div>
				
			</div>
			<?php	include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html';
		if (isset($addFileScript) && $addFileScript) { ?>
			<script>// Добавление названия справа от кнопки Добавить... в областях выбора файлов при выборе файла для загрузки
				
				$(document).on('change', '.btn-file :file', function() {
				  var input = $(this),
				      numFiles = input.get(0).files ? input.get(0).files.length : 1,
				      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
				  input.trigger('fileselect', [numFiles, label]);
				});
				
				$(document).ready( function() {
				    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
				        
				        var input = $(this).parents('.input-group').find(':text'),
				            log = numFiles > 1 ? numFiles + ' files selected' : label;
				        
				        if( input.length ) {
				            input.val(log);
				        } else {
				            if( log ) alert(log);
				        }
				        
				    });
				});
			</script>
		<?php } ?>
		
		<script src="includes/js/jquery.numeric.min.js"></script>
		<script>
			$(document).ready(function() {
				
				$("input[type=\"number\"]").numeric({ decimal: false, negative: false }, function() {this.value = "1"; this.focus(); });
				
				setTimeout(function(){
					$("i.fa[class^=\"text\"]").remove();
				}, 8000);
				
				var submitButtons = $("#admin-settings-form button[type=\"submit\"]");				
				
				function update_textarea_word_count(txt, word_count) {
					var maxLen = 200;
					
					var len = txt.val().length;
					if (len > maxLen) {
						$(word_count).addClass("bg-danger");
						$(submitButtons).attr('disabled', 'disabled');
					} else {
						$(word_count).removeClass("bg-danger");
						$(submitButtons).removeAttr('disabled');
					}
					$(word_count).html(maxLen - len);
				}
				
				<?=$additionalScripts;?>
				
				
				var allSubmitButtons = $("button[type=\"submit\"]");
				
				$(allSubmitButtons).click(function() {
					$(this).html("Сохраняется... <i class=\"fa fa-spinner fa-pulse\"></i>");
					var a = $(this);
					setTimeout(function() {
						$(a).attr('disabled', 'disabled');
						}, 100);
				});
				
				
				$("#password-submit").click(function(e) {
					e.preventDefault();
					
					// обработать форму
					if ($("#old-password").val().length == 0 || $("#password").val().length == 0 || $("#repeat-password").val().length == 0) {
						addNotification('Не все поля заполнены!', 'danger');
						return;
					} else if ($("#password").val().length < 8 || $("#repeat-password").val().length < 8) {
						addNotification('Длина нового пароля должа быть не меньше 8 символов!', 'danger');
						return;
					}

					// отправить форму
					$(this).html("Сохраняется... <i class=\"fa fa-spinner fa-pulse\"></i>").attr('disabled', 'disabled');
					var _this = $(this);
					$.ajax({
						type: "POST",
						url: "admin-settings.php",
						data: {
							'form-name': 'admin-password',
							'old-password': $("#old-password").val(),
							'password': $("#password").val(),
							'repeat-password': $("#repeat-password").val()
							},
						success: function(msg) {
							if (msg.lastIndexOf('danger:', 0) === 0) {
								addNotification(msg.substr('danger:'.length), 'danger');
								$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">").removeAttr('disabled');
							} else {
								addNotification(msg, 'success');
								$("#old-password").val('');
								$("#password").val('');
								$("#repeat-password").val('');
								$(_this).html("Готово <i class=\"fa fa-check\"></i>").removeAttr('disabled');
								setTimeout(function() {$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">");}, 5000);
							}
	          				
						},
						fail: function() {
							addNotification('Произошла ошибка при отправке запроса', 'danger');
							$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">").removeAttr('disabled');
						}
					});
					
				});
				
			});
		</script>

	</body>
</html>