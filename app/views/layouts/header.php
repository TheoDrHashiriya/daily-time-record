<header class="header">
	<div class="hamburger-lines">
		<span class="line line1"></span>
		<span class="line line2"></span>
		<span class="line line3"></span>
	</div>

	<h4>Welcome, <?= htmlspecialchars($_SESSION["first_name"]) ?></h4>

	<div class="right">
		<a href="logout" id="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>

		<div class="notification-container">
			<div class="notification-bell">
				<i class="button fa-solid fa-bell"></i>
				<span class="notification-badge"
					id="notification-badge"><?= count($system_logs["notifications"]["unread"]) ?></span>
			</div>
			<div class="notification-dropdown" id="notification-dropdown">
				<div class="dropdown-header">
					<h3>Notifications</h3>
				</div>

				<div class="dropdown-body">
					<?php if (empty($system_logs["notifications"]["unread"])): ?>
						<div class="notification-item">No notifications.</div>
					<?php else: ?>
						<?php foreach ($system_logs["notifications"]["unread"] as $row): ?>
							<div class="notification-item">
								<h4><?= $row["title"] ?></h4>
								<p><?= $row["content"] ?></p>
							</div>
						<?php endforeach ?>
					<?php endif ?>
				</div>

				<div class="dropdown-footer">
					<a class="nav" href="?page=system-logs" data-target="system-logs-section">View All</a>
				</div>
			</div>
		</div>

		<div id="theme-toggle"></div>
	</div>
</header>