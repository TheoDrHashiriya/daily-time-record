<?php
session_start();

include_once "./classes/user.php";
$userObj = new User();

$errors = [];

if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$user["username"] = trim(htmlspecialchars($_POST["username"]));
	$user["password"] = trim(htmlspecialchars($_POST["password"]));


	if (empty($user["username"])) {
		$errors["username"] = "Please enter your username.";
	}

	if (empty($user["password"])) {
		$errors["password"] = "Please enter your password.";
	}

	if (empty(array_filter($errors))) {
		$userData = $userObj->getUserByUsername($user["username"]);

		if (!$userData || !password_verify($user["password"], $userData["password"])) {
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			$errors["general"] = "Incorrect credentials.";
		} else {
			session_start();
			$_SESSION["user_id"] = $userData["id"];
			$_SESSION["username"] = $userData["username"];
			$_SESSION["role"] = $userData["role"];
			$_SESSION["first_name"] = $userData["first_name"];
			header("Location: index.php");
			exit;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="./css/global.css">
	<link rel="stylesheet" href="./css/form.css">
	<script src="./js/theme.js" defer></script>
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
				<input value="<?= $user["username"] ?? "" ?>" placeholder="johnsmith123" type="text" name="username"
					id="username">
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