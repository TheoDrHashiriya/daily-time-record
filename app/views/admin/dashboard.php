<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/dashboard.css">
	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
	<script src="<?= JS_URL ?>/ajax-handler.js" defer></script>
	<script src="<?= JS_URL ?>/time.js" defer></script>
	<script src="<?= JS_URL ?>/modal.js" defer></script>
	<script src="<?= JS_URL ?>/modal-prefill.js" defer></script>
	<script src="<?= JS_URL ?>/notification-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/page-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/sidebar-toggle.js" defer></script>
	<script src="<?= JS_URL ?>/theme.js"></script>
</head>

<!-- GENERAL MODALS -->
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

<body>
	<div class="dashboard-container">
		<?php include VIEWS_PATH . "/layouts/header.php"; ?>
		<?php include VIEWS_PATH . "/layouts/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<div class="row">
				<?php include VIEWS_PATH . "/components/cards/time.php"; ?>
			</div>

			<!-- KPI CONTAINER -->
			<div class="row">
				<div class="card">
					<div class="card-value"><?= $records["total"] ?></div>
					<div class="card-title">Total Records</div>
				</div>

				<div class="card">
					<div class="card-value"><?= $records["unclosed"] ?></div>
					<div class="card-title">Unclosed Records</div>
				</div>

				<div class="card">
					<div class="card-value"><?= $users["total"] ?></div>
					<div class="card-title">Total Users</div>
				</div>

				<div class="card">
					<div class="card-value"><?= $departments["total"] ?></div>
					<div class="card-title">Total Departments</div>
				</div>
			</div>
		</main>

		<main class="main section" id="system-logs-section">
			<?php include VIEWS_PATH . "/components/modals/system-log-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/system-log-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/system-log-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/system-logs.php"; ?>
		</main>

		<main class="main section" id="records-section">
			<?php include VIEWS_PATH . "/components/modals/record-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/record-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/record-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/records.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/record-types.php"; ?>
		</main>

		<main class="main section" id="users-section">
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