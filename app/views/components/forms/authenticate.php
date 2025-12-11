<div class="card">
	<h2 class="header">Authenticate</h2>
	<form action="authenticate" method="post" id="authenticate">
		<label for="code">4-Digit User Number</label>
		<input placeholder="****" type="text" name="user_number" id="user_number" maxlength="4" autofocus>
		<p class="error"><?= $errors["user_number"] ?? "" ?></p>
		<button class="main" type="submit">Authenticate</button>
	</form>
</div>