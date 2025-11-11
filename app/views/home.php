<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daily Time Record | Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/dashboard.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>public/css/home.css">
	<script src="<?= BASE_URL ?>public/js/theme.js"></script>
	<script src="<?= BASE_URL ?>public/js/clock.js" defer></script>
	<script src="<?= BASE_URL ?>public/js/home/auth-form-info-pane.js" defer></script>
</head>

<body>
	<header>
		<div class="left">
			<h3>theonary</h3>
		</div>

		<div class="right">
			<button type="button" id="theme-toggle"></button>
		</div>
	</header>

	<main>
		<div class="left">
			<section id="table-pane">
				<?php include "partials/records-table.php" ?>
			</section>
		</div>

		<div class="right">
			<section id="time-pane">
				<?php include "partials/time-card.php" ?>
			</section>

			<section id="info-pane" style="display: none;">
				<div class="card" id="info">
					<h4>
						<?php
						if (!isset($_SESSION["error"]))
							echo $_SESSION["success"] ?? "";
						echo $_SESSION["error"] ?? "";
						?>
					</h4>
				</div>
			</section>

			<section id="authentication-pane">
				<?php include "partials/home/auth-form.php" ?>
			</section>
		</div>
	</main>
</body>

</html>