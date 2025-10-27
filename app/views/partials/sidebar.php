<section id="sidebar" class="sidebar">
	<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]): ?>
		<div class="top">
			<a class="nav" href="#" data-target="home-section">Home</a>
			<a class="nav" href="#" data-target="records-section">Records</a>

			<?php if ($userRole === "admin"): ?>
				<a class="nav" href="#" data-target="users-section">Users</a>
			<?php endif; ?>
		</div>

		<div class="bottom">
			<a id="logout" href="logout">Logout</a>
		</div>
	<?php endif; ?>
</section>