<?php
session_start();

require_once "../controllers/UserController.php";
$userController = new UserController();

$errors = [];
$success = false;

if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$first_name = trim($_POST["first_name"]);
	$last_name = trim($_POST["last_name"]);
	$middle_name = trim($_POST["middle_name"] ?? "");
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);

	if (!$first_name)
		$errors["first_name"] = "Please enter your first name.";

	if (!$last_name)
		$errors["last_name"] = "Please enter your last name.";

	if (!$username)
		$errors["username"] = "Please enter your username.";

	if (!$password)
		$errors["password"] = "Please enter your password.";

	if (empty($errors)) {
		$result = $userController->register($first_name, $last_name, $middle_name, $username, $password);

		if (isset($result["success"])) {
			header("Location: login.php");
			exit;
		} else {
			$errors["general"] = $result["error"];
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
	<link rel="stylesheet" href="../css/global.css">
	<link rel="stylesheet" href="../css/form.css">
	<script src="../js/theme.js" defer></script>
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
				<input value="<?= htmlspecialchars($_POST["first_name"] ?? "") ?>" placeholder="John" type="text"
					name="first_name" id="first_name">
				<p class="error"><?= $errors["first_name"] ?? "" ?></p>

				<label for="middle_name">Middle Name (Optional)</label>
				<input value="<?= htmlspecialchars($_POST["middle_name"] ?? "") ?>" placeholder="Amery" type="text"
					name="middle_name" id="middle_name">
				<p class="error"><?= $errors["middle_name"] ?? "" ?></p>

				<label for="last_name">Last Name</label>
				<input value="<?= htmlspecialchars($_POST["last_name"] ?? "") ?>" placeholder="Smith" type="text"
					name="last_name" id="last_name">
				<p class="error"><?= $errors["last_name"] ?? "" ?></p>

				<label for="username">Username</label>
				<input value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" placeholder="JohnSmith123" type="text"
					name="username" id="username">
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