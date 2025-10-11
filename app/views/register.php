<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>css/global.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/form.css">
	<script src="<?= BASE_URL ?>js/theme.js" defer></script>
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

				<p>Already have an account? <a href="./login">Login here</a>.</p>
			</form>
		</div>
	</div>
</body>

</html>