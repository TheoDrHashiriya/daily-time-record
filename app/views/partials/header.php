<header class="header">
		<div class="hamburger-lines">
			<span class="line line1"></span>
			<span class="line line2"></span>
			<span class="line line3"></span>
		</div>

		<?php if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]): ?>
			<a class="login" href="login">Login</a>
		<?php endif; ?>

		<button type="button" id="theme-toggle"></button>
	</header>