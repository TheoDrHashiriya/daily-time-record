<div class="modal-container" id="user-create">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Add User</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form method="post" action="create-user">
			<input type="hidden" name="created_by" id="created_by" value="<?= $_SESSION["user_id"] ?? "" ?>">

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
					<label for="password">Password</label>
					<input type="text" name="password" id="password">
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
					<select id="department" name="department">
						<?php foreach ($departments["data"] as $dep): ?>
							<option value="<?= $dep["id"] ?>">
								<?= $dep["department_name"] ?>
							</option>
						<?php endforeach ?>
					</select>
					<p class="error"><?= $errors["department"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>