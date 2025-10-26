<div class="card">
	<h2 class="header">Users</h2>
	<?php if (!empty($users)): ?>
		<div class="record-table">
			<table>
				<thead>
					<tr>
						<th>ID No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Username</th>
						<th>Role</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($users as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["last_name"]) ?></td>
							<td><?= htmlspecialchars($row["first_name"]) ?></td>
							<td>
								<?= $row["middle_name"] ? htmlspecialchars($row["middle_name"]) : "<em>No middle name.</em>" ?>
							</td>
							<td><?= htmlspecialchars($row["username"]) ?></td>
							<td><?= htmlspecialchars($row["role"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No users found.</p>
	<?php endif; ?>
</div>