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
			<a class="navbar-brand" href="admin-dashboard.php"><?php
// 				$companyLogo = $database->getValueByShortName("LOGO")["BLOB_VALUE"];
// 				if (!$companyLogo) {
					echo $companyName;
// 				} else {
// 					echo '<img src="data:image/jpeg;base64,'.base64_encode($companyLogo['BLOB_VALUE']).'" title="'.$companyName.'" alt="'.$companyName.'">';
// 				}
			?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="admin-dashboard.php"><i class="fa fa-fw fa-dashboard"></i> Главная</a></li>
	            
				<li class="divider visible-xs-block"></li>
			
				<li class="hidden-xs hidden-sm"><a href="admin-users-combined-list.php"><i class="fa fa-fw fa-users"></i> Постоянные и недавние пользователи</a></li>
				<li class="visible-xs-block visible-sm-block"><a href="admin-users-combined-list.php?onlyloyals"><i class="fa fa-fw fa-heart"></i> Постоянные пользователи</a></li>
				<li class="visible-xs-block visible-sm-block"><a href="admin-users-combined-list.php?onlyclients"><i class="fa fa-fw fa-users"></i> Недавние пользователи</a></li>
			
				<li class="hidden-xs hidden-sm"><a href="admin-birthdays-list.php"><i class="fa fa-fw fa-birthday-cake"></i> Дни рождения</a></li>
				<li class="visible-xs-block visible-sm-block"><a href="admin-birthdays-list.php?mobile"><i class="fa fa-fw fa-birthday-cake"></i> Дни рождения</a></li>
			
				<li class="dropdown">
		        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-fw fa-ellipsis-h"></i> Ещё <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">

						<li><a href="admin-settings.php"><i class="fa fa-fw fa-cog"></i> Настройки</a></li>
						<li><a href="admin-help.php"><i class="fa fa-fw fa-support"></i> Помощь</a></li>
						<li><a href="admin-logout.php"><i class="fa fa-fw fa-sign-out"></i> Выйти</a></li>
								
					</ul>
		        </li>
        
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>