<?php
session_start();

if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
	exit;
}

require_once "../controllers/UserController.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);

	$userController = new UserController();
	$result = $userController->login($username, $password);

	if (isset($result["errors"])) {
		$errors = $result["errors"];
	}

	if (isset($result["success"])) {
		header("Location: index.php");
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="../css/global.css">
	<link rel="stylesheet" href="../css/form.css">
	<script src="../js/theme.js" defer></script>
</head>

<body>
	<div class="container">
		<div class="form-box" id="login-form">
			<div class="container" id="top">
				<a href=".">Home</a>
				<button id="theme-toggle"></button>
			</div>

			<h1 class="title">Login</h1>
			<form action="" method="post">
				<label for="username">Username</label>
				<input value="<?= htmlspecialchars($username ?? "") ?>" placeholder="johnsmith123" type="text"
					name="username" id="username">
				<p class="error"><?= $errors["username"] ?? "" ?></p>

				<label for="title">Password</label>
				<input placeholder="john12345" type="password" name="password" id="password">
				<p class="error"><?= $errors["password"] ?? "" ?></p>

				<p class="error-general"><?= $errors["general"] ?? "" ?></p>

				<button class="login" type="submit">Login</button>

				<p>Don't have an account? <a href="./register.php" style="text-decoration: none;">Register here</a>.</p>
			</form>
		</div>
	</div>
</body>

</html>