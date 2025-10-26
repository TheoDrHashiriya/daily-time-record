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
</head>

<body>
	<?= include "partials/header.php" ?>
	<?= include "partials/sidebar.php" ?>

	<main class="main section" id="home-section">
		<?= include "partials/welcome-logout-card.php" ?>
		<?= include "partials/time-card.php" ?>

		<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]) {
			include "partials/records-newest-table.php";
			if ($userRole === "admin")
				include "more admin stuff please";

			if ($userRole == "employee")
				include "partials/time-buttons.php";
		} ?>
	</main>

	<?php if (
		isset($_SESSION["is_logged_in"])
		&& $_SESSION["is_logged_in"]
		&& $userRole === "admin"
	): ?>
		<main class="main section" id="users-section">
			<?= include "partials/admin/users-table.php"; ?>
		</main>

		<main class="main section" id="records-section">
			<?= include "partials/admin/records-table.php"; ?>
		</main>
	<?php endif; ?>
</body>

</html>