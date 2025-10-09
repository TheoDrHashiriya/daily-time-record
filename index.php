<?php
require_once "./classes/user.php";
require_once "./classes/daily-time-record.php";
session_start();

$dtr = new DailyTimeRecord();

if (isset($_SESSION["user_id"]))
	$userObj = new User($_SESSION["user_id"]);

$search = $genre = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$search = isset($_GET["search"]) ? trim(htmlspecialchars($_GET["search"])) : "";
	$genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Theonary</title>
	<link rel="stylesheet" href="./css/global.css">
	<link rel="stylesheet" href="./css/index.css">
	<script src="./js/theme.js" defer></script>
	<script src="./js/clock.js" defer></script>
</head>

<body>
	<header class="header">
		<div class="hamburger-lines">
			<span class="line line1"></span>
			<span class="line line2"></span>
			<span class="line line3"></span>
		</div>
		<?php if (!isset($_SESSION["user_id"])): ?>
			<a class="login" href="login.php">Login</a>
			<a class="register" href="register.php">Register</a>
		<?php endif; ?>
		<button type="button" id="theme-toggle"></button>
	</header>

	<section class="sidebar">

	</section>

	<main class="main">
		<?php if (isset($_SESSION["user_id"])): ?>
			<div class="card" id="welcome">
				<h3>Welcome, <?= $_SESSION["first_name"] ?>!</h3>
				<a href="logout.php" id="logout">Logout</a>
			</div>
		<?php endif; ?>

		<div class="card" id="time">
			<h1 class="title">Time Now</h1>
			<h2 id="clock"></h2>
		</div>

		<?php if (isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "employee"): ?>
			<div class="card" id="time-in">
				<a href="timein.php" id="time-in">Time In</a>
			</div>
			<div class="card" id="time-out">
				<a href="" id="time-in">Time Out</a>
			</div>
		<?php endif; ?>

		<?php if (isset($_SESSION["user_id"])):
			if ($_SESSION["user_type"] == "admin"):
				$records = $dtr->getAllRecords();
				?>
				<div class="card">
					<!-- <form action="" method="get">
						<label for="search">Search:</label>
						<input type="search" name="search" id="search" value="<?= $search ?>">

						<select name="genre" id="genre">
							<option value="">ALL</option>
							<option value="History" <?= (isset($book["genre"]) && $book["genre"] == "History") ? "selected" : "" ?>>
								History
							</option>
							<option value="Science" <?= (isset($book["genre"]) && $book["genre"] == "Science") ? "selected" : "" ?>>
								Science
							</option>
							<option value="Fiction" <?= (isset($book["genre"]) && $book["genre"] == "Fiction") ? "selected" : "" ?>>
								Fiction
							</option>
						</select>

						<button type="submit">Search</button>
					</form> -->

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
									foreach ($records as $row):
										// $message = "Are you sure you want to delete user " . $userObj["username"] . "?";
										?>
										<tr>
											<td><?= htmlspecialchars($row["record_date"]) ?></td>
											<td><?= htmlspecialchars($row["user_id"]) ?></td>
											<td><?= htmlspecialchars(date("h:i A", strtotime($row["time_in"]))) ?></td>
											<td><?= $row["time_out"] ? htmlspecialchars(strtotime($row["time_out"])) : "<em>No time out record.</em>" ?></td>
											<!-- <td>
											<a class="button-edit" href="edit-book.php?id=<?= $book["id"] ?>"><button>Edit</button></a>
											<a class="button-delete" href="delete-book.php?id=<?= $book["id"] ?>"
												onclick="return confirm('<?= $message ?>')"><button>Delete</button></a>
										</td> -->
										</tr>
									<?php endforeach;
								else: ?>
									<tr>
										<td colspan="4">No records found.</td>
									</tr>
								<?php endif; ?>
							</tbody>
					</div>
				</div>
			<?php endif;
			if ($_SESSION["user_type"] == "employee"): ?>
			<?php endif;
		endif; ?>

		<div class="card"></div>
		<div class="card"></div>
	</main>
</body>

</html>