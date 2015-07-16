<?php
	require '../core/session.php';
	$protector->protectPageSetMinAccessLevel('MANAGER');
	
	if (!isset($_GET['id_client'])) {
		die("error");
	}
	
	$userInfo = $database->getClient($_GET['id_client']);
?>
<input type="hidden" name="form-name" value="edit-user-info">
<h1><i class="fa fa-user"></i> <?=$userInfo['COMPANY_NAME'];?></h1>

<div class="page-wrapper close-follow form-groups-v-condensed">
	
	<h2>Компания</h2>
	<div class="form-horizontal">
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Название</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['COMPANY_NAME'];?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Email</label>
			<p 		class="col-sm-6 form-control-static"><a href="mailto:<?=$userInfo['EMAIL'];?>"><?=$userInfo['EMAIL'];?></a></p>
		</div>
<!--
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Комментарий</label>
			<p 		class="col-sm-6 form-control-static">< ? =$userInfo['COMMENT'];?></p>
		</div>
-->
	</div>
	
</div>
<div class="page-wrapper close-follow form-groups-v-condensed">
	
	<h2>Роутеры</h2>
	<div class="form-horizontal">
	
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Логин роутера</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['ROUTER_LOGIN'];?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Токен роутера</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['ROUTER_PASSWORD'];?></p>
		</div>
				
	</div>
</div>
<div class="page-wrapper close-follow form-groups-v-condensed">
	
	<h2>Статистика</h2>
	<div class="form-horizontal">
	
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Посетителей за сегодня</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['LOGIN_ACT_COUNT_TODAY'];?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Посетителей за месяц</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['LOGIN_ACT_COUNT_MONTH'];?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Посетителей за год</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['LOGIN_ACT_COUNT_YEAR'];?></p>
		</div>
				
	</div>
</div>
<div class="page-wrapper close-follow form-groups-v-condensed">
	
	<h2>Служебная информация</h2>
	<div class="form-horizontal">
	
		<?php if ($database->meetsAccessLevel('ROOT')) { ?>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">ID</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['ID_DB_USER'];?></p>
		</div>
		<?php } ?>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Дата создания</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['DATE_CREATED'];?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Дата последней редакции записи</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['DATE_MODIFIED'] ? $userInfo['DATE_MODIFIED'] : 'не было изменений';?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Последний раз изменен</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['DB_USER_MODIFIED'].' ('.$userInfo['ID_DB_USER_MODIFIED'].')';?></p>
		</div>
		<div class="form-group">
			<label 	class="col-sm-6 control-label">Ведется обслуживание</label>
			<p 		class="col-sm-6 form-control-static"><?=$userInfo['IS_ACTIVE'] == 'T' ? 'да' : '<i class="fa fa-warning text-warning"></i> нет';?></p>
		</div>
		<?php if ($database->meetsAccessLevel('ROOT')) { ?>
			<?php if ($userInfo['NUM_FAILED_ATTEMPTS']) { ?>
				<div class="form-group">
					<label 	class="col-sm-6 control-label">Количество неверных попыток ввода пароля</label>
					<p 		class="col-sm-6 form-control-static"><i class="fa fa-warning text-warning"></i> <?=$userInfo['NUM_FAILED_ATTEMPTS'];?></p>
				</div>
			<?php } ?>
			<?php if ($userInfo['LAST_FAILED_ATTEMPT']) { ?>
				<div class="form-group">
					<label 	class="col-sm-6 control-label">Последняя неудачная попытка входа</label>
					<p 		class="col-sm-6 form-control-static"><i class="fa fa-warning text-warning"></i> <?=$userInfo['LAST_FAILED_ATTEMPT'];?></p>
				</div>
			<?php } ?>
		<?php } ?>
		<?php if ($userInfo['UNLOCK_AT']) { ?>
			<div class="form-group">
				<label 	class="col-sm-6 control-label">Заблокирован по причине многократного неверного ввода пароля</label>
				<p 		class="col-sm-6 form-control-static"><i class="fa fa-warning text-warning"></i> да, будет разблокирован <?=$userInfo['UNLOCK_AT']?></p>
			</div>
		<?php } ?>
		
	</div>
</div>
<div class="page-wrapper">
	<div class="action-buttons-mid-way-panel only-child">
		<button class="btn btn btn-black gradient" id="close-right-hand-side">Закрыть <i class="fa fa-times"></i></button>
	</div>
</div>
<script>makeInfoDOMConnections();</script>