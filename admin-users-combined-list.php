<?php
	include 'includes/base/admin.php';
	$database->prepareForDefaultTableQueries();
	
	$drawClients = true;
	$drawLoyals = true;

	if (isset($_GET["onlyclients"])) {
		$drawLoyals = false;
	} else if (isset($_GET["onlyloyals"])) {
		$drawClients = false;
	}
	$drawBoth = $drawClients && $drawLoyals;
	$drawOnlyOne = !$drawBoth;

?><!DOCTYPE html>
<html lang="ru"<?php if ($drawBoth) {echo ' style="overflow-y:hidden;"';} ?>>
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title><?php
			if ($drawLoyals && $drawOnlyOne) echo "Постоянные клиенты"; else echo "Клиенты";
		?> <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page">
		<?php
			if (!$drawBoth) {
				$notificationLink = "admin-users-combined-list.php";
				include 'includes/modules/switchToDesktopVersionNote.php'; 
			}
		?>
		
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>
	
			<?php if ($drawBoth) { ?>
			<div class="row">
				<div class="col-md-6">
					<?php include 'includes/modules/loyalClientsTable.php'; ?>
				</div>
				<div class="col-md-6">
					<?php include 'includes/modules/clientsTable.php'; ?>
				</div>
			</div>
			<?php
				} else if ($drawClients) { 
					include 'includes/modules/clientsTable.php';
				} else if ($drawLoyals) {
					include 'includes/modules/loyalClientsTable.php';
				}
				include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<?php if ($drawBoth) { ?><script type="text/javascript" src="includes/js/loyalClientsAndClients.js"></script><?php } ?>
	</body>
</html>