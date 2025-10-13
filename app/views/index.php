<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>css/index.css">
	<script src="<?= BASE_URL ?>js/theme.js" defer></script>
	<script src="<?= BASE_URL ?>js/clock.js" defer></script>
	<script src="<?= BASE_URL ?>js/page-toggle.js" defer></script>
</head>

<body>
	<header class="header">
		<div class="hamburger-lines">
			<span class="line line1"></span>
			<span class="line line2"></span>
			<span class="line line3"></span>
		</div>

		<?php if (!isset($_SESSION["user_id"])): ?>
			<a class="login" href="login">Login</a>
			<a class="register" href="register">Register</a>
		<?php endif; ?>
		<button type="button" id="theme-toggle"></button>
	</header>

	<section class="sidebar">
		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["role"] === "admin"): ?>
				<a href="#" data-target="home-section">Home</a>
				<a href="#" data-target="users-section">Users</a>
				<a href="#" data-target="records-section">Records</a>
			<?php endif;
		endif; ?>

	</section>

	<main class="main section" id="home-section">
		<?php if (isset($_SESSION["user_id"])): ?>
			<div class="card" id="welcome">
				<h4>Welcome, <?= htmlspecialchars($_SESSION["first_name"]) ?>!</h4>
				<a href="logout" id="logout">Logout</a>
			</div>
		<?php endif; ?>

		<div class="card" id="time">
			<h2 class="title header">Time Now</h2>
			<h2 id="clock"></h2>
		</div>

		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["role"] === "admin"): ?>
				<div class="card">
					<h2 class="header">Daily Time Records</h2>
					<?php if (!empty($records)): ?>
						<div class="record-table">
							<table>
								<thead>
									<tr>
										<th>Date</th>
										<th>User</th>
										<th>Time In</th>
										<th>Time Out</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($records as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td>
												<?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p>No records found.</p>
					<?php endif; ?>
				</div>
			<?php endif;

			if ($_SESSION["role"] == "employee"): ?>
				<div class="card">
					<h2 class="header">Daily Time Records</h2>
					<?php if (!empty($records)): ?>
						<div class="record-table">
							<table>
								<thead>
									<tr>
										<th>Date</th>
										<th>User</th>
										<th>Time In</th>
										<th>Time Out</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($records as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td>
												<?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p>No records found.</p>
					<?php endif; ?>
				</div>

				<div class="card" id="time-in">
					<a href="timein" id="time-in">Time In</a>
				</div>
				<div class="card" id="time-out">
					<a href="timeout" id="time-in">Time Out</a>
				</div>
			<?php endif;
		endif; ?>
	</main>

	<main class="main section" id="users-section">
		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["role"] === "admin"): ?>
				<div class="card">
					<h2 class="header">Users</h2>
					<?php if (!empty($users)): ?>
						<div class="record-table">
							<table>
								<thead>
									<tr>
										<th>ID No.</th>
										<th>Last Name</th>
										<th>First Name</th>
										<th>Middle Name</th>
										<th>Username</th>
										<th>Role</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($users as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["id"]) ?></td>
											<td><?= htmlspecialchars($row["last_name"]) ?></td>
											<td><?= htmlspecialchars($row["first_name"]) ?></td>
											<td>
												<?= $row["middle_name"] ? htmlspecialchars($row["middle_name"]) : "<em>No middle name.</em>" ?>
											</td>
											<td><?= htmlspecialchars($row["username"]) ?></td>
											<td><?= htmlspecialchars($row["role"]) ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p>No users found.</p>
					<?php endif; ?>
				</div>
			<?php endif;

			if ($_SESSION["role"] == "employee"): ?>
				<div class="card">
					<h2 class="header">Daily Time Records</h2>
					<?php if (!empty($records)): ?>
						<div class="record-table">
							<table>
								<thead>
									<tr>
										<th>Date</th>
										<th>User</th>
										<th>Time In</th>
										<th>Time Out</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($records as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td>
												<?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p>No records found.</p>
					<?php endif; ?>
				</div>

				<div class="card" id="time-in">
					<a href="timein" id="time-in">Time In</a>
				</div>
				<div class="card" id="time-out">
					<a href="timeout" id="time-in">Time Out</a>
				</div>
			<?php endif;
		endif; ?>
	</main>

	<main class="main section" id="records-section">
		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["role"] === "admin"): ?>
				<div class="card">
					<h2 class="header">Daily Time Records</h2>
					<?php if (!empty($records)): ?>
						<div class="record-table">
							<table>
								<thead>
									<tr>
										<th>Date</th>
										<th>User</th>
										<th>Time In</th>
										<th>Time Out</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($records as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td>
												<?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p>No records found.</p>
					<?php endif; ?>
				</div>
			<?php endif;
		endif; ?>
	</main>
</body>

</html>