<?php
session_start();
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

		<div class="card"></div>
		<div class="card"></div>
		<div class="card"></div>
	</main>
</body>

</html>