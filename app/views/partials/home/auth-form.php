<div class="form-box" id="login-form">
	<h2 class="title">Authenticate</h2>
	<form action="authenticate" method="post">
		<label for="username">Username</label>
		<input value="<?= htmlspecialchars($username ?? "") ?>" placeholder="Username" type="text" name="username"
			id="username">
		<p class="error"><?= $errors["username"] ?? "" ?></p>

		<label for="title">Password</label>
		<input placeholder="Password" type="password" name="password" id="password">
		<p class="error"><?= $errors["password"] ?? "" ?></p>

		<p class="error-general"><?= $errors["general"] ?? "" ?></p>

		<button class="login" type="submit">Authenticate</button>
	</form>
</div>