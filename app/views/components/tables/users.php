<div class="card">
	<div class="top">
		<h2 class="header">Users</h2>
		<?php if ($isAdmin): ?>
			<div class="right actions">
				<button type="button" class="open-button" data-target="#user-create" data-modal-type="user-create">
					<i class="fa-solid fa-user-plus"></i>Add User
				</button>

				<a href="all-users" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
			</div>
		<?php endif ?>
	</div>

	<?php if (empty($users)): ?>
		<p>No users found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if ($isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>ID</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Last Name</th>
						<th>Username</th>
						<th>Role</th>
						<th>Department</th>
						<th>Created By</th>
						<th>Created On</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($users as $row): ?>
						<tr>
							<?php if ($isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#user-edit" data-modal-type="user-edit"
										data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(
										  		json_encode([
										  			"first_name" => $row["first_name"],
										  			"middle_name" => $row["middle_name"],
										  			"last_name" => $row["last_name"],
										  			"username" => $row["username"],
										  			"user_role" => $row["user_role"],
										  			"department" => $row["department"],
										  			"created_at" => $row["created_at"],
										  			"created_by" => $row["created_by"],
										  		])
										  	) ?>">
										<i class="fa-regular fa-pen-to-square"></i>Edit
									</button>

									<button type="button" class="open-button danger" data-target="#user-delete"
										data-modal-type="user-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["first_name"]) ?></td>
							<td>
								<?= $row["middle_name"] ? htmlspecialchars($row["middle_name"]) : "<em>No middle name.</em>" ?>
							</td>
							<td><?= htmlspecialchars($row["last_name"]) ?></td>
							<td><?= htmlspecialchars($row["username"]) ?></td>
							<td><?= htmlspecialchars(App\Services\FormattingService::formatText($row["role_name"])) ?></td>
							<td><?= htmlspecialchars(App\Services\FormattingService::formatText($row["department_name"])) ?></td>
							<td>
								<?= $row["creator_first_name"] ? htmlspecialchars(App\Services\FormattingService::formatFullName(
									$row["creator_first_name"],
									$row["creator_middle_name"],
									$row["creator_last_name"]
								)) : "<em>No creator.</em>" ?>
							</td>
							<td><?= htmlspecialchars(App\Services\FormattingService::formatDate($row["created_at"])) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endif ?>
</div>