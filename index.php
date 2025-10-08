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
	<link rel="stylesheet" href="./css/form.css">
	<link rel="stylesheet" href="./css/index.css">
	<script src="./js/theme.js" defer></script>
	<script src="./js/clock.js" defer></script>
</head>

<body>
	<div class="container">
		<div class="form-box">
			<button type="button" id="theme-toggle"></button>

			<h1 class="title">Time Now</h1>
			<div id="clock"></div>

			<?php if (!isset($_SESSION["user_id"])): ?>
				<p>You are not logged in. Please log in to time in and out.</p>

				<div class="container" id="login-register">
					<a class="login" href="login.php">Login</a>
					<a class="login" href="register.php">Register</a>
				</div>
			<?php else: ?>
				<p><?= ucfirst($_SESSION["user_type"]) ?> Mode</p>
				<p>Welcome, <?= $_SESSION["first_name"] ?>!</p>

				<form action="logout.php" method="post">
					<button type="submit">Logout</button>
				</form>
			<?php endif; ?>

		</div>
	</div>
	</div>
</body>

</html>