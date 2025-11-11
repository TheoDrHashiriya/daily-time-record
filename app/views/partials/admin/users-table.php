<div class="card">
	<div class="top">
		<h2 class="header">Users</h2>
		<div class="right">
			<a href="register">Create New User</a>
			|
			<a href="all-users" target="_blank">Print to PDF</a>
		</div>
	</div>

	<?php if (!empty($users)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Last Name</th>
						<th>Username</th>
						<th>Role</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($users as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["first_name"]) ?></td>
							<td>
								<?= $row["middle_name"] ? htmlspecialchars($row["middle_name"]) : "<em>No middle name.</em>" ?>
							</td>
							<td><?= htmlspecialchars($row["last_name"]) ?></td>
							<td><?= htmlspecialchars($row["username"]) ?></td>
							<td><?= htmlspecialchars(GlobalHelper::formatText($row["role_name"])) ?></td>
							<td>
								<form action="edit-user" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button type="submit">Edit</button>
								</form>

								<form action="delete-user" method="post" class="action">
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete the user <?= $row['username'] ?>? This will also delete all existing records of them.')">Delete</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No users found.</p>
	<?php endif; ?>
</div>