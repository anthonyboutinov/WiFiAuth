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
						
						<form action="admin-dashboard.php" method="post">
							<input type="hidden" name="form-name" value="pretend-to-be">
							<input type="hidden" name="pretend-to-be" value="2">
							<input type="submit" value="Посмотреть админскую панель Chop-Chop">
						</form>

					 	<table class="table table-hover table-condensed">
		 					<tr><td class="text-right">1</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">2</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">3</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">4</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">5</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">6</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">7</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">8</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">9</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">10</td><td class="text-center">Дубровин Добромысл</td></tr>
		 					<tr><td class="text-right">11</td><td class="text-center">Дубровин Добромысл</td></tr>
					 	</table>

				 	</div>
				</div>
			<div class="col-md-8">

				<h1><i class="fa fa-user-plus"></i> Добавить клиента</h1> 
				
				<div class="page-wrapper close-follow">
					
					<h2>Компания</h2>
					<div class="form-horizontal">

						<div class="form-group">
								<label class="col-sm-3 control-label" for="company-name">Название</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="company-name" autocomplete="off" maxlength="255">
								</div>

						</div>

						<div class="form-group">
								<label class="col-sm-3 control-label" for="email">E-mail</label>							
								<div class="col-sm-9">
									<input type="text" class="form-control" name="email" autocomplete="off" maxlength="255">
								</div>
							</div>
					</div>
					
				</div>
				<div class="page-wrapper close-follow">
					
					<h2>Роутеры</h2>
					<div class="form-horizontal">
						
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="mac-adress">MAC-адрес</label>							
							<div class="col-sm-9">
								<input type="text" class="form-control" name="mac-adress" autocomplete="off" maxlength="1024">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="mac-adress">Токен</label>	
							<div class="col-sm-9">
								<div class="input-group">
									<input type="text" class="form-control default-cursor"
										name="router-token" id="router-token" autocomplete="off" maxlength="32" disabled>
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
						  <label class="col-sm-3 control-label" for="mac-adress">Логин</label>							
							<div class="col-sm-9">
								<input type="text" class="form-control" name="login" autocomplete="off" maxlength="255">
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
				
				<div class="page-wrapper">
					<div class="action-buttons-mid-way-panel only-child">
						<button type="button" class="btn btn btn-black gradient">Сохранить <i class="fa fa-floppy-o"></i></button>
						<button type="button" class="btn btn btn-red gradient">Приостановить обслуживание <i class="fa fa-toggle-off"></i></button>
					</div>
				</div>
				
				<div class="page-wrapper">
					<div class="action-buttons-mid-way-panel only-child">
						<button type="button" class="btn btn btn-black gradient">Сохранить и активировать <i class="fa fa-toggle-on"></i></button>
					</div>
				</div>
				 	
         	</div>
    	</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script src="includes/js/superadmin-clients.js"></script>
		
 	</body>
</html>