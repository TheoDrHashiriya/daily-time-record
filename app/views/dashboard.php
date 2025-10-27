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
		<?php include "partials/header.php"; ?>
		<?php include "partials/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<?php include "partials/welcome-logout-card.php"; ?>
			<?php include "partials/time-card.php"; ?>

			<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]) {
				include "partials/records-newest-table.php";
				if ($userRole === "admin") {
					// empty for now
				}

				if ($userRole == "employee")
					include "partials/time-buttons.php";
			} ?>
		</main>

		<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]):
			if ($userRole === "admin"): ?>
				<main class="main section" id="users-section">
					<?php include "partials/admin/users-table.php"; ?>
				</main>

				<main class="main section" id="records-section">
					<?php include "partials/admin/records-table.php"; ?>
				</main>
			<?php endif;
			if ($userRole === "employee"): ?>
				<main class="main section" id="records-section">
					<?php include "partials/records-table.php"; ?>
				</main>
			<?php endif;
		endif; ?>
	</div>
</body>

</html>