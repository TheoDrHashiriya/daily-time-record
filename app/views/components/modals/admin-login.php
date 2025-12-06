<div class="modal-container" id="admin-login">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-arrow-right-to-bracket"></i>Login</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="login-form" method="post" action="login">
			<div class="row">
				<div class="column">
					<label for="username">Username</label>
					<input value="<?= $username ?? "" ?>" type="text" name="username" id="username">
					<p class="error"><?= $errors["username"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="password">Password</label>
					<input type="password" name="password" id="password">
					<p class="error"><?= $errors["password"] ?? "" ?></p>
				</div>
			</div>

			<p class="error-general"><?= $errors["general"] ?? "" ?></p>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button class="danger" type="submit">Go</button>
			</div>
		</form>
	</div>
</div>