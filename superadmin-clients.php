<?php
	include 'includes/core/session.php';
	$protector->protectPageSetMinAccessLevel('MANAGER');
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
						<div class="row head-row">
							
							<div class="col-xs-12">
								<div class="input-group">
									<input type="search" class="form-control" placeholder="Поиск">
									<span class="input-group-btn">
										<button class="btn btn-black" type="button"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
							
							<a href="#" id="order-by-name" class="active">
								<div class="col-xs-<?=$database->meetsAccessLevel('ROOT') ? 4 : 6;?>">
									<i class="fa fa-sort-alpha-asc" title="Сортировать по имени"></i><small> Имя</small>
								</div>
							</a>
							<a href="#" id="order-by-traffic">
								<div class="col-xs-<?=$database->meetsAccessLevel('ROOT') ? 4 : 6;?>">
									<i class="fa fa-sort-amount-desc" title="Сортировать по количеству трафика"></i><small> Трафик</small>
								</div>
							</a>
							<?php if ($database->meetsAccessLevel('ROOT')) { ?>
							<a href="#" id="order-by-id">
								<div class="col-xs-4">
									<i class="fa fa-sort-numeric-asc" title="Сортировать по ID"></i><small> ID</small>
								</div>
							</a>
							<?php } ?>
							
						</div>
					 	<table class="table table-hover table-condensed" id="table">
							<?php include 'includes/modules/superadmin-clients-table.php'; ?>
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