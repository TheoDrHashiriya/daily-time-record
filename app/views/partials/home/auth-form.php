<div class="form-box" id="login-form">
	<!-- <div class="container" id="top">
		<a id="back-button">Back</a>
	</div> -->

	<h1 class="title">Authenticate</h1>
	<form action="" method="post">
		<label for="username">Username</label>
		<input value="<?= htmlspecialchars($username ?? "") ?>" placeholder="johnsmith123" type="text" name="username"
			id="username">
		<p class="error"><?= $errors["username"] ?? "" ?></p>

		<label for="title">Password</label>
		<input placeholder="john12345" type="password" name="password" id="password">
		<p class="error"><?= $errors["password"] ?? "" ?></p>

		<p class="error-general"><?= $errors["general"] ?? "" ?></p>

		<button class="login" type="submit">Login</button>
	</form>
</div>