<div class="card">
	<div class="top">
		<h2 class="header">Daily Time Records</h2>
		<a href="all-events" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
	</div>
	<?php if (!empty($records)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>ID</th>
						<th>User</th>
						<th>Type</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records as $row): ?>
						<tr>
							<td class="actions">
								<button type="button" class="edit-btn action"><i class="fa-regular fa-pen-to-square"></i>Edit</button>
								<input type="hidden" name="row-id" value="<?= $row["id"] ?>">

								<form action="delete-record" method="post" class="action">
									<input type="hidden" name="row-id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete the current record for <?= $row['user'] ?>?')"><i
											class="fa-regular fa-trash-can"></i>Delete</button>
								</form>
							</td>
							<td><?= htmlspecialchars($row["user_id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["event_date_formatted"]) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars($row["event_time_formatted"]) : "<em>No record.</em>" ?>
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