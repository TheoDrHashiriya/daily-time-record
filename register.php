<?php
session_start();

include_once "./classes/user.php";
$userObj = new User();

$errors = [];

if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$user["first_name"] = trim(htmlspecialchars($_POST["first_name"]));
	$user["last_name"] = trim(htmlspecialchars($_POST["last_name"]));
	$user["middle_name"] = trim(htmlspecialchars($_POST["middle_name"]));
	$user["username"] = trim(htmlspecialchars($_POST["username"]));
	$user["password"] = trim(htmlspecialchars($_POST["password"]));

	if (empty($user["first_name"])) {
		$errors["first_name"] = "Please enter your first name.";
	}

	if (empty($user["last_name"])) {
		$errors["last_name"] = "Please enter your last name.";
	}

	if (empty($user["username"])) {
		$errors["username"] = "Please enter your username.";
	} elseif ($userObj->userExists($user["username"])) {
		$errors["username"] = "Username already taken.";
	}

	if (empty($user["password"])) {
		$errors["password"] = "Please enter your password.";
	}

	if (empty(array_filter($errors))) {
		$hashedPass = password_hash($user["password"], PASSWORD_DEFAULT);

		if ($userObj->addUser($user["first_name"], $user["last_name"], $user["middle_name"], $user["username"], $hashedPass)) {
			header("Location: login.php");
		} else {
			echo "Error.";
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
		<div class="form-box" id="register-form">
			<div class="container" id="top">
				<a href=".">Home</a>
				<button id="theme-toggle"></button>
			</div>

			<h1 class="title">Register</h1>
			<form action="" method="post">
				<label for="first_name">First Name</label>
				<input placeholder="John" type="text" name="first_name" id="first_name">
				<p class="error"><?= $errors["first_name"] ?? "" ?></p>

				<label for="middle_name">Middle Name (Optional)</label>
				<input placeholder="Amery" type="text" name="middle_name" id="middle_name">
				<p class="error"><?= $errors["middle_name"] ?? "" ?></p>

				<label for="last_name">Last Name</label>
				<input placeholder="Smith" type="text" name="last_name" id="last_name">
				<p class="error"><?= $errors["last_name"] ?? "" ?></p>

				<label for="username">Username</label>
				<input placeholder="JohnSmith123" type="text" name="username" id="username">
				<p class="error"><?= $errors["username"] ?? "" ?></p>

				<label for="title">Password</label>
				<input placeholder="12345" type="password" name="password" id="password">
				<p class="error"><?= $errors["password"] ?? "" ?></p>

				<button class="register" type="submit">Register</button>

				<p>Already have an account? <a href="./login.php">Login here</a>.</p>
			</form>
		</div>
	</div>
</body>

</html>