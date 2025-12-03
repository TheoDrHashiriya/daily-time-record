<div class="card">
	<div class="top">
		<h2 class="header">Notifications</h2>
	</div>

	<?php if (!empty($notifications)):
		$notifications = App\Helpers\GlobalHelper::sortArrayByColumn($notifications, "created_at");
		?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
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