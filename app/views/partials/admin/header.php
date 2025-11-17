<header class="header">
	<div class="hamburger-lines">
		<span class="line line1"></span>
		<span class="line line2"></span>
		<span class="line line3"></span>
	</div>

	<div class="right">
		<?php if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]): ?>
			<a class="login" href="login">Login</a>
		<?php endif; ?>

		<div class="notification-wrapper" onclick="toggleNotification()">
			<i id="notif-icon" class="fa-solid fa-bell"></i>
			<span class="badge" id="notif-badge"></span>
			<span class="notif-popup">Time In!</span>
		</div>

		<div id="theme-toggle"></div>
	</div>
</header>