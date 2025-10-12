<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>css/form.css">
	<script src="<?= BASE_URL ?>js/theme.js" defer></script>
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

				<p>Don't have an account? <a href="register" style="text-decoration: none;">Register here</a>.</p>
			</form>
		</div>
	</div>
</body>

</html>