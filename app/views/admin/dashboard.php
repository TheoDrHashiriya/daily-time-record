<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/dashboard.css">
	<script src="<?= JS_URL ?>/ajax-handler.js" defer></script>
	<script src="<?= JS_URL ?>/clock.js" defer></script>
	<script src="<?= JS_URL ?>/modal.js" defer></script>
	<script src="<?= JS_URL ?>/modal-prefill.js" defer></script>
	<script src="<?= JS_URL ?>/notification-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/page-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/sidebar-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/theme.js"></script>

	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
</head>

<body>
	<div class="dashboard-container">
		<?php include VIEWS_PATH . "/layouts/header.php"; ?>
		<?php include VIEWS_PATH . "/layouts/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<?php include VIEWS_PATH . "/components/cards/welcome-logout.php"; ?>
			<?php include VIEWS_PATH . "/components/cards/time.php"; ?>

			<div class="row">
				<div class="card">
					<h2>Total Events</h2>
					<h3><?= $kpiData["events_total"] ?></h3>
				</div>

				<div class="card">
					<h2>Unclosed Records</h2>
					<h3><?= $kpiData["events_unclosed"] ?></h3>
				</div>

				<div class="card">
					<h2>Total Users</h2>
					<h3><?= $kpiData["users_total"] ?></h3>
				</div>
			</div>

			<div class="row">
				<div class="card">
					<h2>Total Notifications</h2>
					<h3><?= $kpiData["notifications_total"] ?></h3>
				</div>

				<div class="card">
					<h2>Total Departments</h2>
					<h3><?= $kpiData["departments_total"] ?></h3>
				</div>
			</div>
		</main>

		<main class="main section" id="notifications-section">
			<?php include VIEWS_PATH . "/components/modals/notification-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/notification-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/notification-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/notifications.php"; ?>
		</main>

		<main class="main section" id="records-section">
			<?php include VIEWS_PATH . "/components/cards/message.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/record-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/record-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/record-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/records.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/record-types.php"; ?>
		</main>
		
		<main class="main section" id="users-section">
			<?php include VIEWS_PATH . "/components/cards/message.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/users.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/user-roles.php"; ?>
		</main>

		<main class="main section" id="departments-section">
			<?php include VIEWS_PATH . "/components/modals/department-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/department-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/department-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/departments.php"; ?>
		</main>
	</div>
</body>

</html>