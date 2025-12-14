<div class="card">
	<div class="top">
		<h2 class="header"></i>User Roles</h2>
		<!-- <div class="right actions">
			<button type="button" class="open-button" data-target="#create-user-role" data-modal-type="create-user-role">
				<i class="fa-solid fa-user-tag"></i>Add User Role
			</button>
		</div> -->
	</div>

	<?php if (empty($user_roles["data"])): ?>
		<p>No user roles found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<!-- <th>Actions</th> -->
						<th>ID</th>
						<th>Role Name</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($user_roles["data"] as $row): ?>
						<tr>
							<!-- <td class="actions">
								<button type="button" class="open-button" data-target="#user-role-edit"
									data-modal-type="user-role-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
									  	htmlspecialchars(json_encode(["role_name" => $row["role_name"]])) ?>">
									<i class="fa-regular fa-pen-to-square"></i>Edit
								</button>

								<button type="button" class="open-button danger" data-target="#user-role-delete"
									data-modal-type="user-role-delete" data-entity-id="<?= $row["id"] ?>">
									<i class="fa-regular fa-trash-can"></i>Delete
								</button>
							</td> -->
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["role_name_formatted"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>