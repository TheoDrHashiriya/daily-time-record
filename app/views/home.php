<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daily Time Record | Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/index.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/home.css">
	<script src="<?= BASE_URL ?>public/js/theme.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/clock.js" defer></script>
	<!-- <script src="<?= BASE_URL ?>public/js/home/auth-form.js" defer></script> -->
</head>

<body>
	<header>
		header
		<button type="button" id="theme-toggle"></button>
	</header>

	<main>
		<div class="left">
			left
			<section class="table-pane">
				<?php include "partials/records-table.php" ?>
			</section>
		</div>

		<div class="right">
			right
			<section class="info-pane">
				<?php include "partials/time-card.php" ?>
			</section>

			<section class="authentication-pane">
				<?php include "partials/home/auth-form.php" ?>
			</section>
		</div>
	</main>
</body>

</html>