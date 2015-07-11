<?php
	include 'includes/core/session.php';
	$protector->protectPageSetMinAccessLevel('MANAGER');
	
	$database->prepareForDefaultTableQueries();
?>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/superadmin-clients.js"></script>
		<title>Добавление клиента — Панель администрирования Re[Spot]</title>
	</head>
	<body class="admin-page simple-page">

	<?php if ($database->meetsAccessLevel('PRIV_MANAGER')) { ?>
		<div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-black">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h4 class="modal-title">Подтверждение действия <i class="fa fa-lock pull-right"></i></h4>
		      </div>
		      <div class="modal-body">

		      <p>Вы пытаетесь приостановить обслуживание пользователя. Пожалуйста, введите пароль, прежде чем продолжить.</p>

		      <div class="form-horizontal">
					<div class="form-group">
							<label class="col-sm-3 control-label" for="disable-password">Пароль</label>							
							<div class="col-sm-9">
								<input type="password" class="form-control" name="disable-password" id="disable-password" autocomplete="off" maxlength="255">
							</div>
					</div>

				</div>	
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-black" data-dismiss="modal">Отменить</button>
		        <button type="submit" id="disactiveClient" class="btn btn-red">Приостановить <i class="fa fa-ban"></i></button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="enableModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-black">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h4 class="modal-title">Подтверждение действия <i class="fa fa-lock pull-right"></i></h4>
		      </div>
		      <div class="modal-body">

		      <p>Вы пытаетесь возобновить обслуживание пользователя. Пожалуйста, введите пароль, прежде чем продолжить.</p>

		      <div class="form-horizontal">
					<div class="form-group">
							<label class="col-sm-3 control-label" for="enable-password">Пароль</label>							
							<div class="col-sm-9">
								<input type="password" class="form-control" name="enable-password" id="enable-password" autocomplete="off" maxlength="64">
							</div>
					</div>

				</div>	
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-black" data-dismiss="modal">Отменить</button>
		        <button type="submit" id="activeClient" class="btn btn-black">Возобновить <i class="fa fa-plus-circle"></i></button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>

	<?php } ?>
		
		<div class="container glass-panel">
			<?php include 'includes/base/superadmin-navbar.php'; ?>			
			<div class = "row">
				<div class = "col-md-4">
					<h1><i class="fa fa-users"></i> Клиенты</h1> 
					<div class="page-wrapper">
					 	<table class="table table-hover table-condensed">
							<?php
								$dbusers = $database->getDBUsers();
								if ($dbusers->num_rows > 0) {
									$i = 0;
									while($row = $dbusers->fetch_assoc()) {
										$i++;
									?>
									<tr>
										<td class="text-left superadmin-clients-popover-container"><a href="#" data-toggle="popover" data-placement="right" 
										data-title="Информация о клиенте"  
										data-content='<table class="no-word-wrap"><tr><td>Логин:</td><td><?=$row['LOGIN'];?></td></tr>
											<tr><td>Email:</td><td><?=$row['EMAIL'];?></td></tr>
											<tr><td>Логин&nbsp;роутера:</td><td><?=$row['ROUTER_LOGIN'];?></td></tr>
											<tr><td>Пароль&nbsp;роутера:</td><td><?=htmlentities($row['ROUTER_PASSWORD'], ENT_QUOTES);?></td></tr></table>'>
										<?=$row['COMPANY_NAME'];?></a></td>

										<?php if ($database->meetsAccessLevel('ROOT')) { ?>
											<td class="text-right">
												<form action="admin-dashboard.php" method="post">
													<input type="hidden" name="form-name" value="pretend-to-be">
													<input type="hidden" name="pretend-to-be" value="<?=$row['ID_DB_USER'];?>">
													<button type="submit" class="btn btn-link" data-toggle="tooltip" data-placement="left" title="Просмотреть личный кабинет">
														<i class="fa fa-line-chart"></i>
													</button>
												</form>
											</td>
										<?php }
											
										if ($database->meetsAccessLevel('PRIV_MANAGER')) { 
											
											if ($row['IS_ACTIVE'] =='T') { ?>
												<td class="text-right">
													<a href="#" data-id="enabled" data-id-db-user="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Приостановить обслуживание">
														<i class="fa fa-circle" ></i>
													</a>
												</td>
											<?php } else { ?>
												<td class="text-right">
													<a href="#" data-id="disabled" data-id-db-user="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Возобновить обслуживание">
														<i class="fa fa-circle-thin"></i>
													</a>
												</td>
											<?php }
												
										} ?>
									</tr>
							<?php 
									}
								} else { ?>
									<tr><td colspan="1" class="text-center">Пусто</td></tr>
							<?	} ?>
						</table>

					</div>
				</div>
			<div class="col-md-8">
				<?php include 'includes/modules/superadminClientsModule.php' ?> 
			</div>
			</div>
			<?php include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/js/superadmin.php'; ?>
		<script type="text/javascript" src="includes/js/jquery.alphanum.js"></script>
		<script src="includes/js/superadmin-clients.js"></script>

 	</body>
</html>