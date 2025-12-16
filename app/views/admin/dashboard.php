<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= ASSETS_URL ?>/tom-select/dist/css/tom-select.css">
	<link rel="stylesheet" href="<?= CSS_URL ?>/pages/dashboard.css">
	<link rel="stylesheet" href="<?= VENDOR_URL ?>/fortawesome/font-awesome/css/all.min.css" />
	<script src="<?= ASSETS_URL ?>/chart.js/dist/chart.umd.js"></script>
	<script src="<?= JS_URL ?>/chart.js.defaults.js"></script>
	<script src="<?= ASSETS_URL ?>/tom-select/dist/js/tom-select.complete.min.js"></script>
	<!-- <script src="<?= JS_URL ?>/form-input.js" defer></script> -->
	<script src="<?= JS_URL ?>/ajax-handler.js" defer></script>
	<script src="<?= JS_URL ?>/time.js" defer></script>
	<script src="<?= JS_URL ?>/modal.js" defer></script>
	<script src="<?= JS_URL ?>/modal-prefill.js" defer></script>
	<script src="<?= JS_URL ?>/notification.js" defer></script>
	<script src="<?= JS_URL ?>/sections.js" defer></script>
	<script src="<?= JS_URL ?>/sidebar.js" defer></script>
	<script src="<?= JS_URL ?>/theme.js"></script>
	<script src="<?= JS_URL ?>/modal-user-qr.js" defer></script>
</head>

<body>
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

	<div class="dashboard-container">
		<?php include VIEWS_PATH . "/layouts/header.php"; ?>
		<?php include VIEWS_PATH . "/layouts/sidebar.php"; ?>

		<main class="main section" id="home-section">
			<div class="row">
				<?php include VIEWS_PATH . "/components/cards/time.php"; ?>
			</div>

			<!-- KPI CONTAINER -->
			<div class="kpi row">

				<div class="card">
					<div class="card-title">Total Records</div>
					<div class="card-value"><?= $records["total"] ?></div>
				</div>

				<div class="card">
					<div class="card-title">Unclosed Records</div>
					<div class="card-value"><?= $records["unclosed"] ?></div>
				</div>

				<div class="card">
					<div class="card-title">Total Users</div>
					<div class="card-value"><?= $users["total"] ?></div>
				</div>

				<div class="card">
					<div class="card-title">Total Departments</div>
					<div class="card-value"><?= $departments["total"] ?></div>
				</div>
			</div>

			<!-- CHARTS CONTAINERS -->
			<div class="chart row">
				<div class="card">
					<div class="card-title">Attendance Summary (All Time)</div>
					<canvas id="present-late-absent"></canvas>
					<script>const totals = <?= json_encode($charts["totals"]) ?></script>
					<script src="<?= JS_URL ?>/dashboard/charts/present-late-absent.js"></script>
				</div>

				<div class="column">
					<div class="card">
						<div class="card-title">Attendance for the Past Week</div>
						<canvas id="attendance-past-week"></canvas>
						<script>const attendancePastWeek = <?= json_encode($charts["attendancePastWeek"]) ?></script>
						<script src="<?= JS_URL ?>/dashboard/charts/attendance-past-week.js"></script>
					</div>

					<div class="card">
						<div class="card-title">Worst Offenders</div>
						<canvas id="top-late-absent"></canvas>
						<script>const topLateAbsent = <?= json_encode($charts["topLateAbsent"]) ?></script>
						<script src="<?= JS_URL ?>/dashboard/charts/top-late-absent.js"></script>
					</div>
				</div>
			</div>

			<div class="chart row">
				<div class="card">
					<div class="card-title">Top Late</div>
					<canvas id="top-late"></canvas>
					<script>const topLate = <?= json_encode($charts["topLate"]) ?></script>
					<script src="<?= JS_URL ?>/dashboard/charts/top-late.js"></script>
				</div>

				<div class="card">
					<div class="card-title">Top Absent</div>
					<canvas id="top-absent"></canvas>
					<script>const topAbsent = <?= json_encode($charts["topAbsent"]) ?></script>
					<script src="<?= JS_URL ?>/dashboard/charts/top-absent.js"></script>
				</div>
			</div>
		</main>

		<main class="main section" id="system-logs-section">
			<?php include VIEWS_PATH . "/components/modals/system-log-create.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/system-log-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/system-log-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/system-logs.php"; ?>
			<?php include VIEWS_PATH . "/components/tables/system-log-types.php"; ?>
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
			<?php include VIEWS_PATH . "/components/modals/user-qr.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/regenerate-qr.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-edit.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-delete.php"; ?>
			<?php include VIEWS_PATH . "/components/modals/user-records.php"; ?>
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