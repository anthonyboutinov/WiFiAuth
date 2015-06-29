<nav class="navbar">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Навигация</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><?=$companyName;?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				
				<li><a href="superadmin-dashboard.php"><i class="fa fa-fw fa-dashboard"></i> Главная</a></li>
				<li><a href="superadmin-clients.php"><i class="fa fa-fw fa-dashboard"></i> Клиенты</a></li>
	            
				<li class="divider visible-xs-block"></li>

						<li><a href="superadmin-settings.php"><i class="fa fa-fw fa-cog"></i> Настройки</a></li>
						<li><a href="superadmin-help.php"><i class="fa fa-fw fa-support"></i> Помощь</a></li>
						<li><a href="admin-logout.php"><i class="fa fa-fw fa-sign-out"></i> Выйти</a></li>
        
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>