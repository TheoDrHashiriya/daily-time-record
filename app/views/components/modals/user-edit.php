<div class="modal-container" id="user-edit">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Edit User</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form method="post" action="edit-user">
			<input type="hidden" name="entity_id" value="">

			<div class="row">
				<div class="column">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name">
					<p class="error"><?= $errors["first_name"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="middle_name">Middle Name <em>(Optional)</em></label>
					<input type="text" name="middle_name" id="middle_name">
					<p class="error"><?= $errors["middle_name"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name">
					<p class="error"><?= $errors["last_name"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="username">Username</label>
					<input type="text" name="username" id="username">
					<p class="error"><?= $errors["username"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="email">Email <em>(Optional)</em></label>
					<input type="email" name="email" id="email">
					<p class="error"><?= $errors["email"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="password">New Password <em>(Optional)</em></label>
					<input type="password" name="password" id="password">
					<p class="error"><?= $errors["password"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="user_role">Role</label>
					<select id="user_role" name="user_role">
						<?php foreach ($user_roles["data"] as $role): ?>
							<option value="<?= $role["id"] ?>">
								<?= $role["role_name_formatted"] ?>
							</option>
						<?php endforeach ?>
					</select>
					<p class="error"><?= $errors["user_role"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="department">Department</label>
					<select id="department" name="department" required>
						<?php foreach ($departments["data"] as $dep): ?>
							<option value="<?= $dep["id"] ?>">
								<?= $dep["department_name"] ?>
							</option>
						<?php endforeach ?>
					</select>
					<p class="error"><?= $errors["department"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="created_by">Created By</label>
					<select id="created_by" name="created_by">
						<option value="">None</option>
						<?php foreach ($users["admins"] as $user): ?>
							<option value="<?= $user["id"] ?>">
								<?= $user["admins"]["full_name_formatted"] ?>
							</option>
						<?php endforeach ?>
					</select>
					<p class="error"><?= $errors["created_by"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="created_at">Created At</label>
					<input id="created_at" type="datetime-local" step="1" name="created_at" required>
					<p class="error"><?= $errors["created_at"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>