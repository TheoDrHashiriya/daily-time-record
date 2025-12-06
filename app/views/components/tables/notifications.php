<div class="card">
	<div class="top">
		<h2 class="header">Notifications</h2>
		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="right actions">
				<button type="button" class="open-button" data-target="#notification-create"
					data-modal-type="notification-create">
					<i class="fa-solid fa-user-plus"></i>Add Notification
				</button>

				<a href="all-notifications" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
			</div>
		<?php endif ?>
	</div>

	<?php if (empty($notifications)): ?>
		<p>No notifications found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>ID</th>
						<th>Title </th>
						<th>Content</th>
						<th>Created By</th>
						<th>Created On</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($notifications as $row): ?>
						<tr>
							<?php if (isset($isAdmin) && $isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#notification-edit"
										data-modal-type="notification-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(
										  		json_encode([
										  			"title" => $row["title"],
										  			"content" => $row["content"],
										  			"created_by" => $row["created_by"],
										  			"created_at" => $row["created_at"],
										  		])
										  	) ?>">
										<i class="fa-regular fa-pen-to-square"></i>Edit
									</button>

									<button type="button" class="open-button danger" data-target="#notification-delete"
										data-modal-type="notification-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["title"]) ?></td>
							<td><?= htmlspecialchars($row["content"]) ?></td>
							<td><?= htmlspecialchars($row["created_by_full_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["created_on_formatted"] . ", " . $row["created_at_formatted"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>