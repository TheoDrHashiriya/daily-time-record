<div class="card">
	<div class="top">
		<h2 class="header">Notifications</h2>
		<div class="right">
			<a href="all-notifications" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
		</div>
	</div>
	<?php if (!empty($notifications)):
		$notifications = App\Helpers\GlobalHelper::sortArrayByColumn($notifications, "created_at");
		?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>ID</th>
						<th>Title </th>
						<th>Content</th>
						<th>Created By</th>
						<th>Created At</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($notifications as $row): ?>
						<tr>
							<td class="actions">
								<form action="edit-notification" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button type="submit"><i class="fa-regular fa-pen-to-square"></i>Edit</button>
								</form>

								<form action="delete-notification" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete this notification?')"><i
											class="fa-regular fa-trash-can"></i>Delete</button>
								</form>
							</td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["title"]) ?></td>
							<td><?= htmlspecialchars($row["content"]) ?></td>
							<td><?= htmlspecialchars($row["created_by"]) ?></td>
							<td>
								<?= App\Helpers\GlobalHelper::formatDate($row["created_at"]) . ", at " . App\Helpers\GlobalHelper::formatTime($row["created_at"]) ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No notifications found.</p>
	<?php endif; ?>
</div>