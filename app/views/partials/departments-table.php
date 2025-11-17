<div class="card">
	<div class="top">
		<h2 class="header">Departments</h2>	
	</div>
	<?php if (!empty($departments)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Created At</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($departments as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["id"]) ?></td>
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