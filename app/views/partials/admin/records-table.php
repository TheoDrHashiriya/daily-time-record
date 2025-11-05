<div class="card">
	<h2 class="header">Daily Time Records</h2>
	<?php if (!empty($records)): ?>
		<div class="record-table">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>User</th>
						<th>Type</th>
						<th>Date</th>
						<th>Time</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td><?= htmlspecialchars(GlobalHelper::formatEventType($row["type_name"])) ?></td>
							<td><?= htmlspecialchars(GlobalHelper::formatDate($row["event_time"])) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars(GlobalHelper::formatTime($row["event_time"])) : "<em>No record.</em>" ?>
							</td>
							<td>
								<form action="edit-record" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button type="submit">Edit</button>
								</form>

								<form action="delete-record" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete the current record for <?= $row['user'] ?>?')">Delete</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No records found.</p>
	<?php endif; ?>
</div>