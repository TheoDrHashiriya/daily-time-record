<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daily Time Record | theonary</title>
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/home.css">
	<!-- Theming script must start early to avoid flash of light mode -->
	<script src="<?= JS_URL ?>/theme.js"></script>
	<script src="<?= JS_URL ?>/home/auth-form.js" defer></script>
	<script src="<?= JS_URL ?>/ajax-handler.js" defer></script>
	<script src="<?= JS_URL ?>/modal.js" defer></script>
	<script src="<?= JS_URL ?>/time.js" defer></script>

	<?php if (isset($_SESSION["user_number_is_admin"])):
		if ($_SESSION["user_number_is_admin"] === true): ?>
			<script>document.addEventListener("DOMContentLoaded", () => showLoginUsernamePasswordModal())</script>
		<?php elseif ($_SESSION["user_number_is_admin"] === false): ?>
			<script src="<?= ASSETS_URL ?>/jsqr/dist/jsQR.js" defer></script>
			<script src="<?= JS_URL ?>/home/qr-code.js" defer></script>
			<script>document.addEventListener("DOMContentLoaded", () => showLoginQrCodeModal())</script>
		<?php endif;
	endif ?>

	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
</head>

<body>
	<!-- MODALS -->
	<?php include VIEWS_PATH . "/components/modals/success.php"; ?>
	<?php include VIEWS_PATH . "/components/modals/info.php"; ?>
	<?php include VIEWS_PATH . "/components/modals/error.php"; ?>

	<?php if (isset($_SESSION["message"]["success"])): ?>
		<script>document.addEventListener("DOMContentLoaded", () => showSuccessModal())</script>
	<?php elseif (isset($_SESSION["message"]["info"])): ?>
		<script>document.addEventListener("DOMContentLoaded", () => showInfoModal())</script>
	<?php elseif (isset($_SESSION["message"]["error"])): ?>
		<script>document.addEventListener("DOMContentLoaded", () => showErrorModal())</script>
	<?php endif;
	unset($_SESSION["message"]) ?>

	<?php include VIEWS_PATH . "/components/modals/login-qr-code.php" ?>
	<?php include VIEWS_PATH . "/components/modals/login-username-password.php" ?>

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

			<section id="authentication-pane">
				<?php include VIEWS_PATH . "/components/forms/authenticate.php" ?>
			</section>
	</main>
</body>

</html>