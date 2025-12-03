<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daily Time Record | Theonary</title>
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/home.css">
	<script src="<?= JS_URL ?>/theme.js"></script>
	<script src="<?= JS_URL ?>/clock.js" defer></script>
	<script src="<?= JS_URL ?>/home/auth-form-info-pane.js" defer></script>

	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
</head>

<body>
	<header>
		<div class="left">
			<h3>theonary</h3>
		</div>

		<div class="right">
			<div id="theme-toggle"></div>
	</header>

	<main>
		<div class="left">
			<section id="table-pane">
				<?php include VIEWS_PATH . "/components/tables/records.php" ?>
			</section>
		</div>

		<div class="right">
			<section id="time-pane">
				<?php include VIEWS_PATH . "/components/cards/time.php" ?>
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
				<?php include VIEWS_PATH . "/components/forms/auth-form.php" ?>
				</form>
		</div>
		</section>
		</div>
	</main>
</body>

</html>