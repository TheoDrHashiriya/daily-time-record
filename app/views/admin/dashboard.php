<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/dashboard.css">
	<script src="<?= BASE_URL ?>public/js/theme.js"></script>
	<script src="<?= BASE_URL ?>public/js/clock.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/page-toggle.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/sidebar-toggle.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/notification-toggle.js" defer></script>

	<link rel="stylesheet" href="<?= BASE_URL ?>vendor/fortawesome/font-awesome/css/all.min.css" />
</head>

<body>
	<div class="dashboard-container">
		<?php include __DIR__ . "/../partials/admin/header.php"; ?>
		<?php include __DIR__ . "/../partials/admin/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<?php include __DIR__ . "/../partials/admin/welcome-logout-card.php"; ?>
			<?php include __DIR__ . "/../partials/time-card.php"; ?>

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
			<?php include __DIR__ . "/../partials/admin/notifications-table.php"; ?>
		</main>

		<main class="main section" id="records-section">
			<?php include __DIR__ . "/../partials/admin/records-table.php"; ?>
		</main>

		<main class="main section" id="users-section">
			<?php include __DIR__ . "/../partials/admin/users-table.php"; ?>
		</main>

		<main class="main section" id="departments-section">
			<?php include __DIR__ . "/../partials/admin/departments-table.php"; ?>
		</main>
	</div>
</body>

</html>