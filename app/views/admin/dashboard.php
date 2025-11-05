<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/index.css">
	<script src="<?= BASE_URL ?>public/js/theme.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/clock.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/page-toggle.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/sidebar-toggle.js" defer></script>
</head>

<body>
	<div class="dashboard-container">
		<?php include __DIR__ . "/../partials/admin/header.php"; ?>
		<?php include __DIR__ . "/../partials/admin/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<?php include __DIR__ . "/../partials/admin/welcome-logout-card.php"; ?>
			<?php include __DIR__ . "/../partials/time-card.php"; ?>
		</main>

		<main class="main section" id="users-section">
			<?php include __DIR__ . "/../partials/admin/users-table.php"; ?>
		</main>

		<main class="main section" id="records-section">
			<?php include __DIR__ . "/../partials/admin/records-table.php"; ?>
		</main>
	</div>
</body>

</html>