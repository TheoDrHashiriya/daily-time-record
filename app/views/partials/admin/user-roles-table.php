<div class="card">
	<div class="top">
		<h2 class="header">User Roles</h2>
		<div class="right">
			<a href="register"><i class="fa-solid fa-user-plus"></i>Create New User Role</a>
			<a href="all-users" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
		</div>
	</div>

	<?php

	use App\Services\FormattingService;

	if (!empty($users)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>ID</th>
						<th>Role Name</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($user_roles as $row): ?>
						<tr>
							<td class="actions">
								<button type="button" class="open-button" data-target="#edit-user-role"
									data-modal-type="edit-user-role" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
									  	htmlspecialchars(json_encode(["role_name" => $row["role_name"]])) ?>">
									<i class="fa-regular fa-pen-to-square"></i>Edit
								</button>

								<button type="button" class="open-button danger" data-target="#delete-user-role"
									data-modal-type="delete-user-role" data-entity-id="<?= $row["id"] ?>">
									<i class="fa-regular fa-trash-can"></i>Delete
								</button>
							</td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars(App\Services\FormattingService::formatText($row["role_name"])) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No users found.</p>
	<?php endif; ?>
</div>