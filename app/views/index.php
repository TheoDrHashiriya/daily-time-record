<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>css/global.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/index.css">
	<script src="<?= BASE_URL ?>js/theme.js" defer></script>
	<script src="<?= BASE_URL ?>js/clock.js" defer></script>
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

	</section>

	<main class="main">
		<?php if (isset($_SESSION["user_id"])): ?>
			<div class="card" id="welcome">
				<h3>Welcome, <?= htmlspecialchars($_SESSION["first_name"]) ?>!</h3>
				<a href="logout" id="logout">Logout</a>
			</div>
		<?php endif; ?>

		<div class="card" id="time">
			<h1 class="title">Time Now</h1>
			<h2 id="clock"></h2>
		</div>

		<?php if (isset($_SESSION["user_id"]) && $_SESSION["role"] == "employee"): ?>
			<div class="card" id="time-in">
				<a href="timein" id="time-in">Time In</a>
			</div>
			<div class="card" id="time-out">
				<a href="timeout" id="time-in">Time Out</a>
			</div>
		<?php endif; ?>

		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["role"] == "admin"): ?>
				<div class="card">
					<h2>Daily Time Records</h2>
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
								<?php if (!empty($records)):
									foreach ($records as $row): ?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td>
												<?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?>
											</td>
											<!-- <td>
											<a class="button-edit" href="edit-book.php?id=<?= $book["id"] ?>"><button>Edit</button></a>
											<a class="button-delete" href="delete-book.php?id=<?= $book["id"] ?>"
												onclick="return confirm('<?= $message ?>')"><button>Delete</button></a>
										</td> -->
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
			<?php endif;
		endif; ?>
	</main>
</body>

</html>