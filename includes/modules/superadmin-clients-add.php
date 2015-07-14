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
					<div class="input-group">
						<input type="text" class="form-control"
							name="router-login" id="router-login" autocomplete="off" maxlength="1024" readonly>
		                <span class="input-group-btn">
							<span class="btn btn-black" id="generate-login">
								Генерировать <i class="fa fa-key"></i>
							</span>
		                </span>
					</div>
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
			<button class="btn btn btn-black gradient" id="close-right-hand-side">Отменить <i class="fa fa-times"></i></button>
		</div>
	</div>
</form>
<script>makeAddClientDOMConnections();</script>