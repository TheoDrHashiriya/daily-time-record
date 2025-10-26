<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]): ?>
	<div class="card" id="welcome">
		<h4>Welcome, <?= htmlspecialchars($_SESSION["first_name"]) ?>!</h4>
		<a href="logout" id="logout">Logout</a>
	</div>
<?php endif; ?>v