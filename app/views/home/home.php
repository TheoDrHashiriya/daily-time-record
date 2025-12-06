<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daily Time Record | Theonary</title>
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/home.css">
	<!-- Theming script must start early to avoid flash of light mode -->
	<script src="<?= JS_URL ?>/theme.js"></script>
	<script src="<?= JS_URL ?>/home/auth-form-info-pane.js" defer></script>
	<script src="<?= JS_URL ?>/home/auth-form-code.js" defer></script>
	<script src="<?= JS_URL ?>/ajax-handler.js" defer></script>
	<script src="<?= JS_URL ?>/clock.js" defer></script>
	<?php if (!empty($_SESSION["code_is_admin"])): ?>
		<script src="<?= JS_URL ?>/modal.js" defer></script>
		<script src="<?= JS_URL ?>/home/show-login-modal.js" defer></script>
	<?php endif ?>

	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
</head>

<body>
	<!-- MODALS -->
	<?php include VIEWS_PATH . "/components/modals/admin-login.php" ?>

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
		</div>
		</section>
		</div>
	</main>
</body>

</html>