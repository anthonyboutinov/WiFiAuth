<?php
	
	$orderBy = 'NAME';
	
	
	if (!isset($database) && isset($_GET['order-by'])) {
		require '../core/session.php';
		$protector->protectPageSetMinAccessLevel('MANAGER');
		
		$database->prepareForDefaultTableQueries();
		$orderBy = $_GET['order-by'];
	}
	
	
?><tbody>
	<tr class="head-row hide-on-collapsed-view">
		<?php if ($database->meetsAccessLevel('ROOT')) { ?><td>
			<?php if ($orderBy == 'ID_DB_USER') { ?><i class="fa fa-sort-asc"></i><?php } ?>
			ID
		</td><?php } ?>
		<td>
			<?php if ($orderBy == 'NAME') { ?><i class="fa fa-sort-asc"></i><?php } ?> 
			Название компании
		</td>
		<td class="hide-on-collapsed-view">Логин</td>
		<td class="hide-on-collapsed-view">
			<?php if ($orderBy == 'TRAFFIC') { ?><i class="fa fa-sort-desc"></i><?php } ?> 
			За месяц
		</td>
		<?php if ($database->meetsAccessLevel('ROOT')) { ?>
		<td class="hide-on-collapsed-view">Дата создания</td>
		<td class="hide-on-collapsed-view">Дата изменения</td>
		<td class="hide-on-collapsed-view">Изменен</td>
		<?php } ?>
		<td colspan="2"></td>
	</tr>
	<?php
	$dbusers = $database->getClients($orderBy);
	if ($dbusers->num_rows > 0) {
		$i = 0;
		while($row = $dbusers->fetch_assoc()) {
			$i++;
		?>
		<tr>
			<?php if ($database->meetsAccessLevel('ROOT')) { ?><td><?=$row['ID_DB_USER'];?></td><?php } ?>
			<td class="text-left superadmin-clients-popover-container">
				<a href="#" data-toggle="right-hand-side-info" data-id-db-user="<?=$row['ID_DB_USER'];?>">
				<strong><?=$row['COMPANY_NAME'];?></strong></a>
			</td>
			
			<td class="hide-on-collapsed-view"><?=$row['LOGIN'];?></td>
			
			<td class="hide-on-collapsed-view text-right"><?=$row['LOGIN_ACT_COUNT_MONTH'];?></td>
			
			<?php if ($database->meetsAccessLevel('ROOT')) { ?>
			<td class="hide-on-collapsed-view text-right"><?=$row['DATE_CREATED'];?></td>
			<td class="hide-on-collapsed-view text-right"><?=$row['DATE_MODIFIED'];?></td>
			<td class="hide-on-collapsed-view"><?=$row['DB_USER_MODIFIED'];?></td>
			<?php } ?>

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
							<span class="hide-on-collapsed-view super-small">Приостановить </span><i class="fa fa-circle" ></i>
						</a>
					</td>
				<?php } else { ?>
					<td class="text-right">
						<a href="#" data-id="disabled" data-id-db-user="<?=$row['ID_DB_USER'];?>" data-toggle="tooltip" data-placement="left" title="Возобновить обслуживание">
							<span class="hide-on-collapsed-view super-small">Возобновить </span><i class="fa fa-circle-thin"></i>
						</a>
					</td>
				<?php }
					
			} else { 
				echo '<td class="text-right">';
				echo '<span class="hide-on-collapsed-view super-small">'.($row['IS_ACTIVE'] =='T' ? 'Вкл.' : 'Выкл.').' </span><i class="fa fa-'.($row['IS_ACTIVE'] =='T' ? 'circle' : 'circle-thin').'" ></i>';
				echo '</td>';
			} ?>
		</tr>
<?php 
		}
	} else { ?>
		<tr><td colspan="1" class="text-center">Пусто</td></tr>
<?	} ?>
</tbody>
<script>makeTableDOMConnections();</script>