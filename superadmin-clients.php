<?php
	include 'includes/core/vars.php';
	$protector->protectPageSetMinAccessLevel('MANAGER');
?>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Добавление клиента — Панель администрирования Re[Spot]</title>
	</head>
	<body class="admin-page simple-page">

		<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title">Внимание!</h4>
		      </div>
		      <div class="modal-body">

		      <p> Вы хотите приостановить обслуживания пользователя.</p> 
		      <p>Подтвердите свои права: введите пароль и логин </p>

		      <div class="form-horizontal">

					<div class="form-group">
						<label class="col-sm-3 control-label" for="access-login">Логин</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="access-login" id="access-login" autocomplete="off" maxlength="255">
						</div>
					</div>

					<div class="form-group">
							<label class="col-sm-3 control-label" for="acess-password">Пароль</label>							
							<div class="col-sm-9">
								<input type="password" class="form-control" name="access-password" id="access-password" autocomplete="off" maxlength="64">
							</div>
					</div>

				</div>	
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-black" data-dismiss="modal">Отмена</button>
		        <button type="button" class="btn btn-red">Приостановить</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>
		
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
										<td class="text-left"><?=$row['LOGIN'];?></td>
										<?php if ($database->meetsAccessLevel('ROOT')) { ?>
											<td class="text-right">
												<form action="superadmin-query.php" method="post">
													<input type="hidden" name="form-name" value="pretend-to-be">
													<input type="hidden" name="pretend-to-be" value="<?=$row['ID_DB_USER'];?>">
													<button type="submit" class="btn btn-link">
														<i class="fa fa-line-chart"></i>
													</button>
												</form>
											</td>
										<?php } ?>
										<?php if ($row['IS_ACTIVE'] =='T') { ?>
											<td class="text-right">
												<a href="#" data-id="enabled" data-idDBUser="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Приостановить обслуживание">
													<i class="fa fa-circle" ></i>
												</a>
											</td>
										<?php } else  { ?>
											<td class="text-right">
												<a href="#" data-id="disabled" data-idDBUser="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Возобновить обслуживание">
													<i class="fa fa-circle-thin"></i>
												</a>
											</td>
										<?php } ?>
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
            	<form action="superadmin-query.php" method="post">
            		<input type="hidden" name="form-name" value="add-user">
					<h1><i class="fa fa-user-plus"></i> Добавить клиента</h1> 
					
					<div class="page-wrapper close-follow">
						
						<h2>Компания</h2>
						<div class="form-horizontal">

							<div class="form-group">
									<label class="col-sm-3 control-label" for="company-name">Название</label>
									<div class="col-sm-9">
									<input type="text" class="form-control" name="company-name" id="company-name" autocomplete="off" maxlength="255">
									</div>
							</div>
							<div class="form-group">
									<label class="col-sm-3 control-label" for="email">E-mail</label>							
									<div class="col-sm-9">
										<input type="text" class="form-control" name="email" id="email" autocomplete="off" maxlength="255">
									</div>
								</div>
						</div>
						
					</div>
					<div class="page-wrapper close-follow">
						
						<h2>Роутеры</h2>
						<div class="form-horizontal">
							
							<div class="form-group">
							  <label class="col-sm-3 control-label" for="router-login">Логин роутера</label>							
								<div class="col-sm-9">
								<input type="text" class="form-control" name="router-login" id="router-login" autocomplete="off" maxlength="1024">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="router-token">Токен</label>	
								<div class="col-sm-9">
									<div class="input-group">
										<input type="text" class="form-control"
											name="router-token" id="router-token" autocomplete="off" maxlength="32" readonly>
						                <span class="input-group-btn">
											<span class="btn btn-black" id="generate-token">
												Генерировать <i class="fa fa-key"></i>
											</span>
						                </span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="page-wrapper close-follow">
							
						<h2>Личный кабинет</h2>
						<div class="form-horizontal">
							<div class="form-group">
							  <label class="col-sm-3 control-label" for="login">Логин</label>							
								<div class="col-sm-9">
									<input type="text" class="form-control" name="login" id="login" autocomplete="off" maxlength="255">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label" for="password">Пароль</label>	
								<div class="col-sm-9">
									<div class="input-group">
										<input type="text" class="form-control"
											name="password" id="password" autocomplete="off" maxlength="32">
						                <span class="input-group-btn">
											<span class="btn btn-black" id="generate-password">
												Генерировать <i class="fa fa-key"></i>
											</span>
						                </span>
									</div>
								</div>
							</div>
							
						</div>
				 	</div>
					<div class="page-wrapper">
						<div class="action-buttons-mid-way-panel only-child">
							<button type="submit" class="btn btn btn-black gradient">Добавить <i class="fa fa-plus"></i></button>
						</div>
					</div>

		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<?php include 'includes/js/superadmin.php'; ?>
		<script src="includes/js/superadmin-clients.js"></script>
 	</body>
</html>