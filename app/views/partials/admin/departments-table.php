<div class="card">
	<div class="top">
		<h2 class="header">Departments</h2>
		<a href="all-departments" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
	</div>
	<?php if (!empty($departments)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>ID</th>
						<th>Name</th>
						<th>Created At</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($departments as $row): ?>
						<tr>
							<td class="actions">
								<form action="edit-record" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button type="submit"><i class="fa-regular fa-pen-to-square"></i>Edit</button>
								</form>

								<form action="delete-record" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete the current record for <?= $row['department_name'] ?>?')"><i
											class="fa-regular fa-trash-can"></i>Delete</button>
								</form>
							</td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							</td>
							<td><?= htmlspecialchars($row["department_name"]) ?></td>
							<td>
								<?= GlobalHelper::formatDate($row["created_at"]) . ", at " . GlobalHelper::formatTime($row["created_at"]) ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No departments found.</p>
	<?php endif; ?>
</div>