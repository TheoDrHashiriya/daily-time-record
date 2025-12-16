<div class="card">
	<div class="top">
		<h2 class="header">Users</h2>
		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="right actions">
				<div class="row">
					<div class="search-container">
						<form action="" method="GET" data-normal="1">
							<input type="search" name="users" id="users" placeholder="Search"
								value="<?= isset($users["search"]) ? htmlspecialchars($users["search"]) : "" ?>">
							<button type="submit"><i class="fa-solid fa-search"></i></button>
						</form>
					</div>
				</div>

				<div class="row">
					<button type="button" class="open-button" data-target="#user-create" data-modal-type="user-create">
						<i class="fa-solid fa-user-plus"></i>Add User
					</button>

					<a href="all-users" target="_blank" class="action">
						<i class="fa-solid fa-print"></i>Print to PDF
					</a>
				</div>
			</div>
		<?php endif ?>
	</div>

	<?php if (empty($users["data"])): ?>
		<p>No users found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>User Number</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Last Name</th>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Username</th>
							<th>Email</th>
						<?php endif ?>
						<th>Role</th>
						<th>Department</th>
						<th>Created By</th>
						<th>Created On</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($users["data"] as $row): ?>
						<tr>
							<?php if (isset($isAdmin) && $isAdmin): ?>
								<td class="actions">
									<?php if ($row["user_role"] !== ROLE_ADMIN): ?>
										<button type="button" class="open-button user-qr" data-target="#user-qr"
											data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
											  	htmlspecialchars(
											  		json_encode([
											  			"base_url" => BASE_URL,
											  			"public_url" => PUBLIC_URL,
											  			"full_name_formatted" => $row["full_name_formatted"],
											  			"user_number" => $row["user_number"],
											  		])
											  	) ?>">
											<i class="fa-solid fa-qrcode"></i>QR
										</button>
									<?php endif ?>

									<button type="button" class="open-button" data-target="#user-edit" data-entity-id="<?= $row["id"] ?>"
										data-entity-data="<?=
											htmlspecialchars(
												json_encode([
													"first_name" => $row["first_name"],
													"middle_name" => $row["middle_name"],
													"last_name" => $row["last_name"],
													"username" => $row["username"],
													"user_role" => $row["user_role"],
													"department" => $row["department"],
													"created_at" => $row["created_at"],
													"created_by" => $row["created_by"] ?? "",
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
							<td><?= htmlspecialchars($row["user_number"]) ?></td>
							<td><?= htmlspecialchars($row["first_name"]) ?></td>
							<td><?= $row["middle_name"] ? htmlspecialchars($row["middle_name"]) : "<em>No middle name.</em>" ?></td>
							<td><?= htmlspecialchars($row["last_name"]) ?></td>
							<?php if (isset($isAdmin) && $isAdmin): ?>
								<td><?= htmlspecialchars($row["username"]) ?></td>
								<td><?= $row["email"] ? htmlspecialchars($row["email"]) : "<em>No email.</em>" ?></td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["role_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["department_name_formatted"]) ?></td>
							<td>
								<?= isset($row["created_by_formatted"]) ? htmlspecialchars($row["created_by_formatted"]) : "<em>No creator.</em>" ?>
							</td>
							<td><?= htmlspecialchars($row["created_at_formatted"]) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endif ?>
</div>