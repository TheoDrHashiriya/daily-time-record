<section class="sidebar">
	<?php if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]):
		if ($userRole === "admin"): ?>
			<a href="#" data-target="home-section">Home</a>
			<a href="#" data-target="users-section">Users</a>
			<a href="#" data-target="records-section">Records</a>
		<?php endif;
	endif; ?>

</section>