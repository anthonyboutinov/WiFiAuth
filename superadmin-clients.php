<?php
	include 'includes/core/vars.php';
	$protector->protectPageSetMinAccessLevel('MANAGER');
?>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Добавить клиента</title>
	</head>
	<body class="admin-page simple-page">
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
										<td class="text-right">
											<?php if ($database->meetsAccessLevel('ROOT')) { ?>
												<form action="admin-dashboard.php" method="post">
													<input type="hidden" name="form-name" value="pretend-to-be">
													<input type="hidden" name="pretend-to-be" value="<?=$row['ID_DB_USER'];?>">
													<button type="submit" class="btn btn-link">
														<i class="fa fa-line-chart"></i>
													</button>
												</form>
											<?php } ?>
										</td>
										<?php if ($row['IS_ACTIVE'] =='T') { ?>
											<td class="text right">
												<a href="#" data-id="enabled" data-idDBUser="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Приостановить обслуживание">
													<i class="fa fa-circle" ></i>
												</a>
											</td>
										<?php } else  { ?>
											<td class="text right">
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
            	<form action = "superadmin-query.php" method="post">
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

					

<!-- 					<div class="page-wrapper">

						<div class="action-buttons-mid-way-panel only-child">

							<button type="button" class="btn btn btn-black gradient">Сохранить <i class="fa fa-floppy-o"></i></button>

							<button type="button" class="btn btn btn-red gradient">Приостановить обслуживание <i class="fa fa-toggle-off"></i></button>

						</div>

					</div>

					

					<div class="page-wrapper">

						<div class="action-buttons-mid-way-panel only-child">

							<button type="button" class="btn btn btn-black gradient">Активировать и сохранить <i class="fa fa-toggle-on"></i></button>

						</div>

					</div>

				 	
				</form>
         	</div>

    	</div> -->

		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/superadmin-clients.js"></script>
 	</body>
</html>