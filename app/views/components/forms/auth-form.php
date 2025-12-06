<div class="card">
	<h2 class="header">Authenticate</h2>
	<form action="authenticate" method="post" id="auth-form-code">
		<label for="code">4-Digit Code</label>
		<input placeholder="****" type="text" name="code" id="code" maxlength="4" autofocus>
		<p class="error"><?= $errors["code"] ?? "" ?></p>
		<button class="main" type="submit">Authenticate</button>
	</form>
</div>